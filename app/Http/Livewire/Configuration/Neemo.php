<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Broker;
use App\Models\Restaurant;
use App\Models\NeemoBroker;
use App\Enums\BrokerType;
use App\Integrations\NeemoIntegration;
use App\Integrations\NeemoIntegrationDistributed;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Neemo extends Component
{
    public NeemoBroker $neemoBroker;
    public $restaurant;
    public $opened = false;

    protected $listeners = ['copied' => 'copiedAlert', 'regenerateCode' => 'generateNeemoCode'];

    public $neemo;

    public $brokerAction;

    protected $rules = [
        'neemoBroker.accessToken' => 'required',
        //'neemoBroker.merchant_id' => 'max:255|required',
        //'neemoBroker.client_secret' => 'max:255|required',
        //'neemoBroker.username' => 'max:255|required',
        //'neemoBroker.password' => 'max:255|required',
        //'neemoBroker.conclude' => 'boolean|nullable',
        'neemoBroker.enabled' => 'boolean|nullable',
        'neemoBroker.acknowledgment' => 'boolean|nullable',
        'neemoBroker.dispatch' => 'boolean|nullable',
        //'neemoBroker.authorizationCode' => 'nullable',     
    ];

    private function userRestaurant(){
        //return Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        return (new RecoverUserRestaurant())->recover(auth()->user()->id);
    }

    public function mount()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        
        try{
            //MVP 1 - Um neemoBrokere por usuário
            //$restaurant = $this->userRestaurant();
            $this->neemoBroker = NeemoBroker::where("restaurant_id", "=", $this->restaurant->id)
                ->firstOrNew();
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Antes de configurar os brokers, é necessário configurar seu delivery.');
        }
    }    

    public function render()
    {
        $this->neemo = Broker::where("enabled", "=", 1)
            ->where("id", "=", BrokerType::Neemo)
            ->first();
        $viewName = 'livewire.configuration.neemo';
        return view($viewName);
    }

    public function save($sendMessage = true)
    {
        $this->brokerAction = BrokerType::Neemo;

        $this->validate();
        try{
            $success = $this->validateNeemoCode();

            if($success){
                $this->neemoBroker->validated = 1;
                $this->neemoBroker->broker_id = BrokerType::Neemo;
                if(intval($this->neemoBroker->id) > 0){
                    $this->update($sendMessage);
                }else{
                    $this->storeNeemo($sendMessage);
                }
            }else{
                $this->neemoBroker->accessToken = "";

                if(intval($this->neemoBroker->id) > 0){
                    $this->neemoBroker->validated = 0;
                    $this->neemoBroker->save();
                }

                $this->simpleAlert('error', 'Não é possível prosseguir. O token de acesso informado é inválido. Contate o Neemo e obtenha um token válido.');
            }

            $this->opened = true;
        }catch(\Exception $e){
            $this->simpleAlert('error', 'Ocorreu um erro desconhecido. Tente novamente mais tarde.');
        }        
	}

    public function storeNeemo($sendMessage = true)
    {
        try {	
            //$restaurant = $this->userRestaurant();
            $this->neemoBroker->restaurant_id = $this->restaurant->id;
            $this->neemoBroker->save();
			if($sendMessage) $this->simpleAlert('success', 'Integração NEEMO registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function update($sendMessage = true)
    {
        try {
            $this->neemoBroker->save();
			if($sendMessage) $this->simpleAlert('success', 'Integração NEEMO registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function delete()
    {
        try {
            $this->neemoBroker->delete();
			$this->simpleAlert('success', 'Integração NEEMO foi excluída.');
            $this->neemoBroker = new neemoBroker();
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar excluir o delivery.');
        }
    }

    public function validateNeemoCode(){
        
        try{
            $neemoIntegration = new NeemoIntegration();
            return $neemoIntegration->validateToken($this->neemoBroker->accessToken);
        }catch(\Exception $e){
            return false;
        }
    }

    public function simpleAlert($type, $message){
        $this->alert($type, $message, [
            'position' =>  'top-end', 
            'timer' =>  5000,  
            'toast' =>  true, 
            'text' =>  '', 
            'confirmButtonText' =>  'Ok', 
            'cancelButtonText' =>  'Cancel', 
            'showCancelButton' =>  false, 
            'showConfirmButton' =>  false, 
        ]);
    }    
}
