<?php

namespace App\Listeners;

use App\Events\FinishedProccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;
use App\Enums\OrderType;

class DispatchOrderOnBroker implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FinishedProccess  $event
     * @return void
     */
    public function handle(FinishedProccess $event)
    {
        try{

            //Dá conhecimento do envio ao ifood
            if(get_class($event->oneBroker) == "App\Models\IfoodBroker"){
                
                $action = "";
                
                if(strtoupper($event->orderBabelized->orderType) == strtoupper(OrderType::DELIVERY)){
                    $action = env('INTEGRATION_IFOOD_DISPATCH_URI');
                }else{
                    $action = env('INTEGRATION_IFOOD_READYTOPICK_URI');
                }

                $payload = [
                    'headers' => [
                        'Authorization' => 'Bearer '. env('INTEGRATION_TOKEN'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    "form_params" => ["ifood_broker_id" => $event->oneBroker->id, "ifood_order_id" => $event->orderBabelized->brokerId]
                ];
                $httpClient = new Client(["verify" => false]);
                $httpResponse = $httpClient->post($action, $payload);
            }else if(get_class($event->oneBroker) == "App\Models\NeemoBroker"){
                $action = env('INTEGRATION_NEEMO_DISPATCH_URI');
                $payload = [
                    'headers' => [
                        'Authorization' => 'Bearer '. env('INTEGRATION_TOKEN'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    "form_params" => ["neemo_broker_id" => $event->oneBroker->id, "neemo_order_id" => $event->orderBabelized->brokerId]
                ];
                $httpClient = new Client(["verify" => false]);
                $httpResponse = $httpClient->post($action, $payload);
            }
        }catch(\Exception $e){
            
        }
    }
}
