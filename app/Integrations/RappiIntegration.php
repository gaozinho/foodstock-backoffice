<?php
namespace App\Util;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class RappiIntegration
{
    protected $client;
    protected $token;

    protected $options = [
        'headers' => [
            'Authorization' => '',        
            'Accept'        => 'application/json',
        ],
    ];  
    protected $tokenExpiresTime = 0;
      
    protected $credentials = [
        'token' => '',
    ];      

	public function __construct($endPoint, $token){
        $this->credentials["token"] = $token;
		$this->client = new Client(["base_uri" => $endPoint]);
    }


    private function handleResponse($response){
        $handledTokenResponse = json_decode($response);
        return $handledTokenResponse;
    }

    private function handleTokenResponse($response){
        if($response->getStatusCode() == 200 && $response->hasHeader('X-Auth-Int')){
            $this->token = $response->getHeader("X-Auth-Int")[0];
            return $this->token; 
        }else{
            throw new \Exception("Falha ao autenticar.", 1);
        }
    }

    private function getOptions(){
        $token = $this->getToken();
        $this->options["headers"]["x-auth-int"] = $token;
        return $this->options;
    }

    private function tokenIsExpired(){
        return time() >= $this->tokenExpiresTime;
    }

    public function getToken(){
        
        if(!$this->tokenIsExpired()){
            return $this->token; 
        } 

        $response = $this->client->post('/api/restaurants-integrations-public-api/login', [
            RequestOptions::JSON => $this->credentials
        ]);

        $token = $this->handleTokenResponse($response);
        $this->options["headers"]["x-auth-int"] = $token;
        $this->tokenExpiresTime = time() + ((30 * 24 * 60 * 60) - 60);
        return $token;
    }

    public function acknowledgment($rappiIds){
        $options = $this->getOptions();
        $data = [];
        foreach($rappiIds as $id){
            $response = $this->client->get('/api/restaurants-integrations-public-api/orders/take/' . $id, $this->getOptions());

            $data[] = [
                "id" => $id, 
                "statusCode" => $response->getStatusCode(), 
                "reason" => $response->getReasonPhrase()
            ]; //$this->handleResponse($response->getBody()->getContents());  
        } 
        return $data;
    }    

    public function events($code = ""){
        $response = $this->client->get('/v1.0/events:polling', $this->getOptions());
        $events = $this->handleResponse($response->getBody()->getContents());
        if(!empty($code)){
            $filteredEvents = [];
            foreach ($events as $event) {             
                if($event->code == $code){
                    $filteredEvents[] = $event;                 
                }
            }
            return $filteredEvents;
        }
        return $events;
    }

    private function mockOrders(){
       $mock = '{
            "order":{
            "id":"10",
            "totalValue":"52.9",
            "createdAt":"2020-10-10 15:00:00",
            "products":[
                {
                    "id":"2096204712",
                    "name":"Tropeiro MEDIO 500g - PROMOÇÃO",
                    "comments":"Um comentário sobre o pedido.",
                    "units":"1",
                    "unitPrice":"21.0",
                    "discountPercentage":"0.0",
                    "unitPriceDiscount":"21.0",
                    "totalPrice":"21.0",
                    "totalPriceWithDiscount":"21.0",
                    "toppings":[
        
                    ]
                },
                {
                    "id":"2095474383",
                    "name":"Feijoadinha 400g + bebida",
                    "comments":"",
                    "units":"1",
                    "unitPrice":"22.9",
                    "discountPercentage":"0.0",
                    "unitPriceDiscount":"22.9",
                    "totalPrice":"31.9",
                    "totalPriceWithDiscount":"31.9",
                    "toppings":[
                        {
                        "id":"12869332",
                        "name":"Mini Guarapan Lata 200ml",
                        "units":"1",
                        "price":"0.0",
                        "toppingCategoryId":"597344"
                        },
                        {
                        "id":"12869637",
                        "name":"Acr\u00e9scimo de Batata Palha 30g (pacote a parte)",
                        "units":"1",
                        "price":"2.0",
                        "toppingCategoryId":"597396"
                        },
                        {
                        "id":"12869644",
                        "name":"Aumentar Mini do Dia para 600g - n8",
                        "units":"1",
                        "price":"6.0",
                        "toppingCategoryId":"597397"
                        },
                        {
                        "id":"12870016",
                        "name":"Pa\u00e7oquinha 20g",
                        "units":"1",
                        "price":"1.0",
                        "toppingCategoryId":"597462"
                        }
                    ]
                }
            ],
            "totalValueWithDiscount":"52.9"
            },
            "client":{
            "id":"35651",
            "firstName":"RAPPI",
            "lastName":"RAPPI",
            "email":"integration.public.publicapi@rappi.com",
            "phone":"3163535",
            "address":"Cll 93 No. 19 - 58"
            },
            "store":{
            "id":"900064322",
            "name":""
            }
        }';
        return [$this->handleResponse($mock)];

    }

    public function orders(){ 

        //return $this->mockOrders();

        $orders = [];
        $response = $this->client->get('/api/restaurants-integrations-public-api/orders', $this->getOptions());
        $statusCode = $response->getStatusCode();
        if($statusCode != 200) throw new \Exception("Erro ao processar pedido. Erro ao contactar servidor.", 2);
        $orders = $this->handleResponse($response->getBody()->getContents());  
        return $orders;
    }

    public function integrateOrder($order, $status = "integration"){
        $response = $this->client->post('/v1.0/orders/' . $order->reference . '/statuses/' . $status, $this->getOptions());
        $confirmation = $this->handleResponse($response->getBody()->getContents());
        return $confirmation;
    }

    public function integrateOrders($orders, $status = "integration"){
        $confirmations = [];
        foreach ($orders as $order) {
            $confirmations[] = $this->integrateOrder($order, $status);
        }
        return $confirmations;
    }
}