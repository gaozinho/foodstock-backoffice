<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Integrations\IfoodIntegrationDistributed;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Jobs\ProcessIfoodItems;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Actions\Help\PerformHealthCheck;

class UserCatalogs extends BaseConfigurationComponent
{
    public $catalogs = [];
    //Job de importação de cardápio
    public $importIfoodRunning = false; 
    public $user_id;   
    public $check;   
    public $restaurant_id;
    public $catalog_id;    

    protected $listeners = [
        'checkImportIfood', 'importIfood'
    ];    

    public function render()
    {
        $this->check = (new PerformHealthCheck())->restaurantsConfigureds();
        $this->user_id = auth()->user()->user_id ?? auth()->user()->id;
        $this->importIfoodRunning = Cache::get('importIfood-' . $this->user_id, false);
        $this->userCatalogs();
        return view('livewire.products.user-catalogs');
    }

    public function userCatalogs()
    {
        try{
            $integration = new IfoodIntegrationDistributed();
            $restaurants = (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);
            foreach($restaurants as $restaurant){
                $this->catalogs[$restaurant->name] = ["restaurant" => $restaurant, "catalogs" => $integration->getCatalogs($restaurant->id)];
            }
            //dd($this->catalogs);
        }catch(\Exception $e){
            $this->simpleAlert('error', 'Não conseguimos listar os seus cardápios do ifood. Verifique se a itegração está configurada.', 5000);
        }
    }       


    public function confirmImportIfood($restaurant_id = 0, $catalog_id = '')
    {
        $this->restaurant_id = $restaurant_id;
        $this->catalog_id = $catalog_id;

        

        $title = 'Deseja importar todos os produtos do IFOOD?';
        $text = 'Esta importação reorganizará TODAS as categorias dos seus produtos, de acordo com o IFOOD. Fique tranquilo, os produtos continuarão com as configurações que você fez (estoque, monitoramento etc). <br /><small>Esta operação pode demorar alguns minutos. Aguarde o completo processamento ou, se preferir, volte mais tarde e observe se o indicador <i class="fas fa-cog fa-spin"></i> ainda está em processamento.</small>';

        if(intval($restaurant_id) > 0){
            $title = 'Deseja importar o cardápio escolhido?';
            $text = 'Esta importação reorganizará TODAS as categorias deste cardápio, de acordo com o IFOOD. Fique tranquilo, os produtos continuarão com as configurações que você fez (estoque, monitoramento etc). <br /><small>Esta operação pode demorar alguns minutos. Aguarde o completo processamento ou, se preferir, volte mais tarde e observe se o indicador <i class="fas fa-cog fa-spin"></i> ainda está em processamento.</small>';
        }

        $this->confirm($title , [
            'html' => $text,
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
            'onConfirmed' => 'importIfood'
        ]);
    }

    public function importIfood(){
        Cache::add('importIfood-' . $this->user_id, true, now()->addMinutes(5));
        //(new ProcessIfoodItems(User::find($this->user_id), $this->restaurant_id, $this->catalog_id))->handle();
        ProcessIfoodItems::dispatch(User::find($this->user_id), $this->restaurant_id, $this->catalog_id);
        $this->simpleAlert('success', 'A importação foi iniciada. Aguarde alguns minutos até a conclusão.', 5000); 
    }

    public function checkImportIfood(){
        $this->importIfoodRunning = Cache::get('importIfood-' . $this->user_id, false);
    }    
}
