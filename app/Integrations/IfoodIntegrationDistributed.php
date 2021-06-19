<?php
namespace App\Integrations;

use App\Models\IfoodBroker;
use App\Integrations\IfoodIntegration;

class IfoodIntegrationDistributed extends IfoodIntegration
{

    public function getUserCode()
    {
        try{
            $httpResponse = $this->httpClient->post($this->broker->usercodeApi, ["form_params" => $this->credentials]);
            $responseUserCode = $this->parseUserCodeResponse($httpResponse->getBody()->getContents());
            return $responseUserCode;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }
    }

    public function associateUserCodeAndBroker($responseUserCode, $brokerId){
        $ifoodBroker = IfoodBroker::findOrFail($brokerId);
        $ifoodBroker->usercode_expires = $this->defineExpirationTime($responseUserCode->expiresIn);
        $ifoodBroker->userCode = $responseUserCode->userCode;
        $ifoodBroker->authorizationCodeVerifier = $responseUserCode->authorizationCodeVerifier;
        $ifoodBroker->verificationUrlComplete = $responseUserCode->verificationUrlComplete;        
        $ifoodBroker->authorizationCode = null;
        $ifoodBroker->accessToken = null;
        $ifoodBroker->refreshToken = null;
        $ifoodBroker->merchant_json = null;
        $ifoodBroker->expiresIn = null;
        $ifoodBroker->name = null;
        $ifoodBroker->corporateName = null; 
        $ifoodBroker->validated_at = null;   
        $ifoodBroker->validated = 0;
        $ifoodBroker->save();
        return $ifoodBroker;
    }

    private function parseUserCodeResponse($response)
    {
        $response = json_decode($response);
        if(is_object($response) && isset($response->userCode)){
            return $response;
        }
        return false;

    }   
    
    private function parseTokenResponse($response)
    {
        $response = json_decode($response);
        if(is_object($response) && isset($response->accessToken)){
            return $response;
        }
        return false;

    }    
    
    private function parseMerchantsResponse($response)
    {
        $response = json_decode($response);
        if(is_array($response) && count($response) > 0){
            return $response;
        }
        return false;

    }     

    public function getToken($authorizationCode, $brokerId)
    {
        $ifoodBroker = IfoodBroker::findOrFail($brokerId);

        if(!$this->tokenIsExpired(strtotime($ifoodBroker->usercode_expires))){ //Token não expirado
            $this->credentials["authorizationCode"] = $authorizationCode;
            $this->credentials["authorizationCodeVerifier"] = $ifoodBroker->authorizationCodeVerifier;
            $httpResponse = $this->httpClient->post($this->broker->authenticationApi, ["form_params" => $this->credentials]);
            $responseToken = $this->parseTokenResponse($httpResponse->getBody()->getContents());
            if($responseToken){
                $ifoodBroker->authorizationCode = $this->credentials["authorizationCode"];
                $ifoodBroker->accessToken = $responseToken->accessToken;
                $ifoodBroker->refreshToken = $responseToken->refreshToken;
                $ifoodBroker->expiresIn = $this->defineExpirationTime($responseToken->expiresIn);
                $ifoodBroker->save();
            }
        }else{
            throw new \Exception("Código expirado. Por favor, comece novamente o processo de integração."); 
        }
        return $this->broker->access_token;

    }

    public function refreshToken($brokerId){
        $ifoodBroker = IfoodBroker::findOrFail($brokerId);
        $this->credentials["grantType"] = "refresh_token";
        $this->credentials["refreshToken"] = $ifoodBroker->refreshToken;
        $httpResponse = $this->httpClient->post($this->broker->authenticationApi, ["form_params" => $this->credentials]);
        $responseToken = $this->parseTokenResponse($httpResponse->getBody()->getContents());  
        if($responseToken){
            $ifoodBroker->accessToken = $responseToken->accessToken;
            $ifoodBroker->refreshToken = $responseToken->refreshToken;
            $ifoodBroker->expiresIn = $this->defineExpirationTime($responseToken->expiresIn);
            $ifoodBroker->save();
        }else{
            throw new \Exception("Não foi possível renovar o token do ifood."); 
        }       
    }

    public function getMerchants($brokerId, $enableOnSuccess = true){
        try{
            $ifoodBroker = IfoodBroker::findOrFail($brokerId);

            if($this->tokenIsExpired(strtotime($ifoodBroker->expiresIn))){
                $this->refreshToken($brokerId);
            }
            
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $ifoodBroker->accessToken;
            $httpResponse = $this->httpClient->get($this->broker->merchantsApi, $this->requestOptions);
            $jsonMerchants = $this->parseMerchantsResponse($httpResponse->getBody()->getContents());

            //TODO - O QUE ACONTECE SE RETORNA MAIS DEUM MERCHANT? TESTAR
            if($enableOnSuccess && $jsonMerchants){
                $this->enableMerchant($ifoodBroker, $jsonMerchants);
            }
            
            return $jsonMerchants;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }        
    }

    public function enableMerchant($ifoodBroker, $jsonMerchants){
        foreach($jsonMerchants as $jsonMerchant){
            $ifoodBroker->merchant_id = $jsonMerchant->id;
            $ifoodBroker->name = $jsonMerchant->name;
            $ifoodBroker->corporateName = $jsonMerchant->corporateName;
            $ifoodBroker->validated = 1;
            $ifoodBroker->validated_at = date("Y-m-d H:i:s");
            $ifoodBroker->merchant_json = json_encode($jsonMerchants);
            $ifoodBroker->save();
        }
    }
}
