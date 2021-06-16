<?php
namespace App\Integrations;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Models\Broker;
use App\Models\RappiBroker;
use App\Enums\BrokerType;

class RappiIntegration
{
    protected $httpClient; //Cliente que requisita no broker
    protected $broker;

    protected $credentials = [ //Credenciais do foodstock
        "grant_type" => "",
        "client_id" => "",
        "client_secret" => "",
        "audience" => "",
    ];

    protected $requestOptions = [ //Cabeçalhos-base
        'headers' => [
            'Authorization' => '',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
    ];

    public function __construct()
    {
        $this->broker = $this->getBroker();
        $this->credentials["grant_type"] = "client_credentials";
        $this->credentials["audience"] = "https://microservices.dev.rappi.com"; //"https://services.rappi.com.br";
        $this->httpClient = new Client(["base_uri" => $this->broker->endpoint, "verify" => false]);
    }

    protected function getBroker(){
        return Broker::findOrFail(BrokerType::Rappi);
    }    

    public function getToken($brokerId)
    {
        
        $rappiBroker = RappiBroker::findOrFail($brokerId);
        $this->credentials["client_id"] = $rappiBroker->client_id;
        $this->credentials["client_secret"] = $rappiBroker->client_secret;
        dd($this->broker->endpoint . $this->broker->authenticationApi, $this->credentials);
        $httpResponse = $this->httpClient->post($this->broker->authenticationApi, [
            RequestOptions::JSON => $this->credentials
        ]);

        
        //$responseToken = $this->parseTokenResponse($httpResponse->getBody()->getContents());        
    }

    private function parseTokenResponse($response)
    {
        $response = json_decode($response);
        if(is_object($response)){
            return $response;
        }
        return false;

    }    
}