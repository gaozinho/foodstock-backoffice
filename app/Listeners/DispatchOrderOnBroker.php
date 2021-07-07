<?php

namespace App\Listeners;

use App\Events\FinishedProccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;

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
            if(get_class($event->oneBroker) == "App\Models\IfoodBroker" && $event->oneBroker->dispatch == 1){
                $payload = [
                    'headers' => [
                        'Authorization' => 'Bearer '. env('INTEGRATION_TOKEN'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    "form_params" => ["ifood_broker_id" => $event->oneBroker->id, "ifood_order_id" => $event->orderBabelized->brokerId]
                ];
                $httpClient = new Client(["verify" => false]);
                $httpResponse = $httpClient->post(env('INTEGRATION_IFOOD_DISPATCH_URI'), $payload);
            }
        }catch(\Exception $e){
            
        }
    }
}
