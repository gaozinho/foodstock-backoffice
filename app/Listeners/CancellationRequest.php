<?php

namespace App\Listeners;

use App\Events\CancellationRequested;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;

class CancellationRequest implements ShouldQueue
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
     * @param  ReadyToPickup  $event
     * @return void
     */
    public function handle(CancellationRequested $event)
    {
        try{
            
            //Dá conhecimento do envio ao ifood
            if(get_class($event->oneBroker) == "App\Models\IfoodBroker"){
                $payload = [
                    'headers' => [
                        'Authorization' => 'Bearer '. env('INTEGRATION_TOKEN'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    "form_params" => [
                        "ifood_broker_id" => $event->oneBroker->id, 
                        "ifood_order_id" => $event->orderBabelized->brokerId,
                        'reason' => $event->reason,
                        'cancellationCode' => $event->cancellationCode,
                    ]
                ];
                $httpClient = new Client(["verify" => false]);
                $httpResponse = $httpClient->post(env('INTEGRATION_IFOOD_CANCELLATION_URI'), $payload);
            }
        }catch(\Exception $e){
            
        }
    }
}
