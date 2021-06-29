<?php
namespace App\Integrations;

use App\Models\Broker;
use GuzzleHttp\Client;
use App\Enums\BrokerType;

class IfoodIntegration
{
    protected $httpClient; //Cliente que requisita no boker
    protected $broker;

    protected $credentials = [ //Credenciais do foodstock
        "grantType" => "",
        "clientId" => "",
        "clientSecret" => "",
        "authorizationCode" => "",
        "authorizationCodeVerifier" => "",
        "refreshToken" => "",
    ];

    protected $requestOptions = [ //Cabeçalhos-base
        'headers' => [
            'Authorization' => '',
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
    ];

    public function __construct()
    {
        $this->broker = $this->getBroker();
        $this->credentials["grantType"] = "authorization_code";
        $this->credentials["clientId"] = $this->broker->client_distributed_id;
        $this->credentials["clientSecret"] = $this->broker->client_distributed_secret;
        $this->httpClient = new Client(["base_uri" => $this->broker->endpoint, "verify" => false]);
    }

    protected function getBroker(){
        return Broker::findOrFail(BrokerType::Ifood);
    }
    
    protected function tokenIsExpired($timestamp)
    {
        return $timestamp <= time();
    }

    protected function defineExpirationTime($ttl)
    {
        return date("Y-m-d H:i:s", time() + ($ttl - 60));
    }    

}
