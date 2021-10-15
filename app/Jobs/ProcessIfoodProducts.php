<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;

use App\Integrations\IfoodIntegrationDistributed;
use App\Actions\Product\Ifood\BrokerProducts;
use App\Actions\ProductionLine\RecoverUserRestaurant;

use App\Models\User;
use App\Models\Restaurant;

class ProcessIfoodProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user->withoutRelations();
    }

    //Evita rodar duas vezes concorrentes
    public function middleware()
    {
        return [(new WithoutOverlapping($this->user->id))->dontRelease()];
    }    

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $integration = new IfoodIntegrationDistributed();
            $brokerProducts = new BrokerProducts();
            $restaurants = Restaurant::where("user_id", "=", $this->user->id)->where("enabled", 1)->orderBy("created_at", "desc")->get();
            $results = [];
            foreach($restaurants as $restaurant){
                $page = 1;
                $limit = 1000;
                do{
                   $products = $integration->getProducts($restaurant->id, $limit, $page);
                   $results[] = $brokerProducts->process($products, $restaurant->id);
                   $page++;
                }while($products->count > 0);
            }
        }catch(\Exception $e){
            $this->fail($e);
        }
    }
}
