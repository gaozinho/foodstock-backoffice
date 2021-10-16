<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Cache;

use App\Actions\Product\Ifood\BrokerCatalog;

use App\Integrations\IfoodIntegrationDistributed;
use App\Actions\Product\Ifood\BrokerProducts;
use App\Actions\ProductionLine\RecoverUserRestaurant;

use App\Enums\BrokerType;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Option;
use App\Models\OptionGroup;

use Illuminate\Support\Facades\DB;

class ProcessIfoodItems implements ShouldQueue
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

 

            //DB::beginTransaction();


            $integration = new IfoodIntegrationDistributed();
            $brokerProducts = new BrokerProducts();
            $restaurants = Restaurant::where("user_id", "=", $this->user->id)->where("enabled", 1)->orderBy("created_at", "desc")->get();  
            $results = [];
            foreach($restaurants as $restaurant){
                Category::where("broker_id", BrokerType::Ifood)->where("restaurant_id", $restaurant->id)->delete();
                OptionGroup::where("broker_id", BrokerType::Ifood)->where("restaurant_id", $restaurant->id)->delete();
             
                $catalogs = $integration->getCatalogs($restaurant->id);
                (new BrokerCatalog($restaurant))->processCatalogs($catalogs);
                //break;
            }
            //DB::commit();
            
        }catch(\Exception $e){
            //DB::rollBack();
            $this->fail($e);
        }finally{
            Cache::forget('importIfood-' . $this->user->id);
        }
    }
}
