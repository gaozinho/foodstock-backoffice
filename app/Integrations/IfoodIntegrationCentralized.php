<?php
namespace App\Integrations;

use App\Models\IfoodBroker;
use App\Integrations\IfoodIntegration;

class IfoodIntegrationCentralized extends IfoodIntegration
{

    public function getToken()
    {
        try{
            if($this->tokenIsExpired(strtotime($this->broker->expires))){
                $httpResponse = $this->httpClient->post($this->broker->authenticationApi, ["form_params" => $this->credentials]);
                $responseToken = $this->parseCentralizedResponse($httpResponse->getBody()->getContents());
                $this->broker->expires = $this->defineExpirationTime($responseToken->expiresIn);
                $this->broker->access_token = $responseToken->accessToken;
                $this->broker->save();
            }
            return $this->broker->access_token;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }
    }    

    public function getMerchant($merchantId, $enableOnSuccess = true){
        try{
            if(empty($this->broker->access_token)) $this->getToken();
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $this->broker->access_token;
            $httpResponse = $this->httpClient->get($this->broker->merchantApi . $merchantId, $this->requestOptions);
            $jsonMerchant = $this->parseResponse($httpResponse->getBody()->getContents());
            if($enableOnSuccess) $this->enableMerchant($merchantId, $jsonMerchant);
            return $jsonMerchant;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }        
    }

    public function enableMerchant($merchantId, $jsonMerchant){
        $ifoodBroker = IfoodBroker::where("merchant_id", $merchantId)->firstOrFail();
        if($jsonMerchant){ //Ativa
            $ifoodBroker->validated = 1;
            $ifoodBroker->validated_at = date("Y-m-d H:i:s");
            $ifoodBroker->merchant_json = json_encode($jsonMerchant);
        }else{ //Desativa
            $ifoodBroker->validated = 0;
            $ifoodBroker->validated_at = null;
            $ifoodBroker->merchant_json = null;
        }
        $ifoodBroker->save();
    }

    private function parseResponse($response)
    {
        $response = json_decode($response);
        if(is_object($response) && isset($response->id)){
            return $response;
        }
        return false;
    }    
}
