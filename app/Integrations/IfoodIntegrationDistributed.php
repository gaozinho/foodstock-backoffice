<?php
namespace App\Integrations;

use App\Models\IfoodBroker;
use App\Integrations\IfoodIntegration;
use GuzzleHttp\Exception\ClientException;
use App\Enums\IfoodApi;


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
        if(is_object($response) || is_array($response)){
            return $response;
        }
        return false;

    }     

    public function getToken($authorizationCode, $brokerId)
    {
        $ifoodBroker = IfoodBroker::findOrFail($brokerId);

        if(!$this->tokenIsExpired(strtotime($ifoodBroker->usercode_expires))){ //Token n??o expirado
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
            throw new \Exception("C??digo expirado. Por favor, comece novamente o processo de integra????o."); 
        }
        return $this->broker->access_token;

    }

    public function refreshToken($brokerId){
        $ifoodBroker = IfoodBroker::findOrFail($brokerId);
        $this->credentials["grantType"] = "refresh_token";
        $this->credentials["refreshToken"] = $ifoodBroker->refreshToken;

        try{
            $httpResponse = $this->httpClient->post($this->broker->authenticationApi, ["form_params" => $this->credentials]);
            $responseToken = $this->parseTokenResponse($httpResponse->getBody()->getContents());
            if($responseToken){
                $ifoodBroker->accessToken = $responseToken->accessToken;
                $ifoodBroker->refreshToken = $responseToken->refreshToken;
                $ifoodBroker->expiresIn = $this->defineExpirationTime($responseToken->expiresIn);
                $ifoodBroker->save();
            }else{
                throw new \Exception("N??o foi poss??vel conectar ao ifood. Reconfigure sua loja."); 
            }     
        }catch(ClientException $e){
            throw new \Exception("N??o foi poss??vel conectar ao ifood. Verifique a configura????o de sua loja."); 
        }
  
    }

    public function getMerchants($brokerId, $enableOnSuccess = true){
        try{
            $ifoodBroker = IfoodBroker::findOrFail($brokerId);

            if($this->tokenIsExpired(strtotime($ifoodBroker->expiresIn))){
                $this->refreshToken($brokerId);
                $ifoodBroker = IfoodBroker::findOrFail($brokerId);
            }
            
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $ifoodBroker->accessToken;
            $httpResponse = $this->httpClient->get($this->broker->merchantsApi, $this->requestOptions);
            $jsonMerchant = $this->parseMerchantsResponse($httpResponse->getBody()->getContents());

            return $jsonMerchant;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }        
    }

    public function getProducts($restaurant_id, $limit, $page){
        try{
            $ifoodBroker = IfoodBroker::where("restaurant_id", $restaurant_id)->firstOrFail();

            if($this->tokenIsExpired(strtotime($ifoodBroker->expiresIn))){
                $this->refreshToken($ifoodBroker->id);
                $ifoodBroker = IfoodBroker::findOrFail($ifoodBroker->id);
            }
            
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $ifoodBroker->accessToken;

            $endpoint = sprintf(IfoodApi::MerchantProducts, $ifoodBroker->merchant_id, $limit, $page);

            $httpResponse = $this->httpClient->get($endpoint, $this->requestOptions);
            $jsonMerchant = $this->parseMerchantsResponse($httpResponse->getBody()->getContents());

            return $jsonMerchant;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }        
    }

    public function getCatalogs($restaurant_id){
        try{
            
            $ifoodBroker = IfoodBroker::where("restaurant_id", $restaurant_id)->firstOrFail();

            if($this->tokenIsExpired(strtotime($ifoodBroker->expiresIn))){
                $this->refreshToken($ifoodBroker->id);
                $ifoodBroker = IfoodBroker::findOrFail($ifoodBroker->id);
            }
            
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $ifoodBroker->accessToken;

            $endpoint = sprintf(IfoodApi::MerchantCatalogs, $ifoodBroker->merchant_id);

            $httpResponse = $this->httpClient->get($endpoint, $this->requestOptions);
            $jsonMerchant = $this->parseMerchantsResponse($httpResponse->getBody()->getContents());

            return $jsonMerchant;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }        
    }

    public function getCategories($restaurant_id, $catalog_id){
        try{
            $ifoodBroker = IfoodBroker::where("restaurant_id", $restaurant_id)->firstOrFail();

            if($this->tokenIsExpired(strtotime($ifoodBroker->expiresIn))){
                $this->refreshToken($ifoodBroker->id);
                $ifoodBroker = IfoodBroker::findOrFail($ifoodBroker->id);
            }
            
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $ifoodBroker->accessToken;

            $endpoint = sprintf(IfoodApi::MerchantCategories, $ifoodBroker->merchant_id, $catalog_id);

            $httpResponse = $this->httpClient->get($endpoint, $this->requestOptions);
            $jsonMerchant = $this->parseMerchantsResponse($httpResponse->getBody()->getContents());

            return $jsonMerchant;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }        
    }       

    public function merchantAvailable($restaurant_id){
        $ifoodBroker = null;
        try{
            $ifoodBroker = IfoodBroker::where("restaurant_id", $restaurant_id)->firstOrFail();

            if($this->tokenIsExpired(strtotime($ifoodBroker->expiresIn))){
                $this->refreshToken($ifoodBroker->id);
                $ifoodBroker = IfoodBroker::where("restaurant_id", $restaurant_id)->firstOrFail();
            }
            
            $this->requestOptions["headers"]["Authorization"] = "Bearer " . $ifoodBroker->accessToken;
            $httpResponse = $this->httpClient->get($this->broker->merchantApi . $ifoodBroker->merchant_id . "/status", $this->requestOptions);

            $jsonMerchant = $this->parseMerchantsResponse($httpResponse->getBody()->getContents());

            return $jsonMerchant;
        }catch(\Exception $exception){
            throw $exception;
            //return false;
        }        
    }    

    public function enableMerchant($ifoodBroker, $jsonMerchants){
        foreach($jsonMerchants as $jsonMerchant){
            //Verifica se este merchant j?? est?? sendo usado
            $usedMerchants = IfoodBroker::where("merchant_id", $jsonMerchant->id)->count();

            //Caso n??o esteja sendo usado, faz o cadastro
            if($usedMerchants == 0){
                $ifoodBroker->merchant_id = $jsonMerchant->id;
                $ifoodBroker->name = $jsonMerchant->name;
                $ifoodBroker->corporateName = $jsonMerchant->corporateName;
                $ifoodBroker->validated = 1;
                $ifoodBroker->validated_at = date("Y-m-d H:i:s");
                $ifoodBroker->merchant_json = json_encode($jsonMerchants);
                $ifoodBroker->save();
                return true;
            }
        }
        return false;
    }
}
