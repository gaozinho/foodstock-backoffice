<?php
namespace App\Integrations;

use App\Models\Broker;
use App\Models\NeemoBroker;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use App\Enums\BrokerType;

class NeemoIntegration
{

    public function __construct()
    {
        $this->broker = $this->getBroker();
        $this->httpClient = new Client(["base_uri" => $this->broker->endpoint, "verify" => false]);
    }

    protected function getBroker(){
        return Broker::findOrFail(BrokerType::Neemo);
    }

    public function validateToken($accessToken)
    {
        try{
            $httpResponse = $this->httpClient->post($this->broker->authenticationApi, ["form_params" => ["token_account" => $accessToken, "limit" => "1", "page" => "1"]]);
            $json = $this->parseHttpResponse($httpResponse);
            if(isset($json->code) && $json->code == 200) return true;
            return false;
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return false;
        }
    }

    public function validateRestaurant($restaurant_id)
    {
        try{
            $neemoBroker = NeemoBroker::where("restaurant_id", $restaurant_id)->first();
            if(!is_object($neemoBroker))  return ["success" => false, "message" => "Não configurado"];
            else if($neemoBroker->validated != 1) return ["success" => false, "message" => "Token inválido"];
            return ["success" => $this->validateToken($neemoBroker->accessToken), "message" => "Integrado"];
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            return ["success" => false, "message" => $exception->getMessage()];
        }
    }    

    protected function parseHttpResponse($httpResponse){
        $json = json_decode($httpResponse->getBody()->getContents());
        return $json;
    }


}
