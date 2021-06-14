<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Broker;
use App\Models\Restaurant;
use App\Models\IfoodBroker;
use App\Models\RappiBroker;
use App\Enums\BrokerType;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Integrations\IfoodIntegration;

class Brokers extends BaseConfigurationComponent
{
    public IfoodBroker $ifoodBroker;
    public RappiBroker $rappiBroker;

    public $ifood;
    public $rappi;

    public $brokerAction;

    protected $rules = [
        //'ifoodBroker.broker_id' => 'numeric|required',
        'ifoodBroker.merchant_id' => 'max:255|required',
        //'ifoodBroker.client_secret' => 'max:255|required',
        //'ifoodBroker.username' => 'max:255|required',
        //'ifoodBroker.password' => 'max:255|required',
        'ifoodBroker.enabled' => 'boolean|nullable',
        'ifoodBroker.acknowledgment' => 'boolean|nullable',

        'rappiBroker.client_id' => 'max:255|required',
        'rappiBroker.client_secret' => 'max:255|required',
        //'rappiBroker.username' => 'max:255|required',
        //'rappiBroker.password' => 'max:255|required',
        'rappiBroker.token' => 'max:255|required',
        'rappiBroker.enabled' => 'boolean|nullable',
        'rappiBroker.acknowledgment' => 'boolean|nullable',        
    ];

    private function userRestaurant(){
        return Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
    }

    public function mount()
    {
        $this->wizardStep = 2;
        try{
            //MVP 1 - Um ifoodBrokere por usuário
            $restaurant = $this->userRestaurant();
            $this->ifoodBroker = IfoodBroker::where("enabled", "=", 1)->where("restaurant_id", "=", $restaurant->id)->firstOrNew();
            $this->rappiBroker = RappiBroker::where("enabled", "=", 1)->where("restaurant_id", "=", $restaurant->id)->firstOrNew();
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Antes de configurar os brokers, é necessário configurar seu delivery.');
        }
    }    

    public function render()
    {

        $this->ifood = Broker::where("enabled", "=", 1)
            ->where("id", "=", BrokerType::Ifood)
            ->first();

        $this->rappi = Broker::where("enabled", "=", 1)
            ->where("id", "=", BrokerType::Rappi)
            ->first();

        $viewName = 'livewire.configuration.brokers';
        if($this->isWizard()) $viewName = 'livewire.configuration.wizard';

        return view($viewName, [
            //"ifood" => $ifood,
            //"rappi" => $rappi
        ])->layout('layouts.app', ['header' => 'Integrações']);
    }

    //IFOOD

    public function saveIfood()
    {
        $this->brokerAction = BrokerType::Ifood;

        $this->validate();

        $this->ifoodBroker->broker_id = BrokerType::Ifood;
        //$this->ifoodBroker->enabled = intval($this->ifoodBroker->enabled);
        //$this->ifoodBroker->acknowledgment = intval($this->ifoodBroker->acknowledgment);
        //$this->ifoodBroker->validated = intval($this->ifoodBroker->validated);
		if(intval($this->ifoodBroker->id) > 0){
            $this->updateIfood();
		}else{
			$this->storeIfood();
		}

        //Valida integração com IDCLIENTE
        $integration = new IfoodIntegration();
        $this->ifoodBroker->validated = $integration->getMerchant($this->ifoodBroker->merchant_id, true) == true;

	}

    public function storeIfood()
    {
        try {	
            $restaurant = $this->userRestaurant();
            $this->ifoodBroker->restaurant_id = $restaurant->id;
            $this->ifoodBroker->save();
			$this->simpleAlert('success', 'Integração IFOOD registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function updateIfood()
    {
        try {
            $this->ifoodBroker->save();
			$this->simpleAlert('success', 'Integração IFOOD registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function deleteIfood()
    {
        try {
            $this->ifoodBroker->delete();
			$this->simpleAlert('success', 'Integração IFOOD foi excluída.');
            $this->ifoodBroker = new ifoodBroker();
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar excluir o delivery.');
        }
    }     

    //RAPPI

    public function saveRappi()
    {

        $this->brokerAction = BrokerType::Rappi;

        $this->validate();

        $this->rappiBroker->broker_id = BrokerType::Rappi;
        $this->rappiBroker->enabled = intval($this->rappiBroker->enabled);
        $this->rappiBroker->acknowledgment = intval($this->rappiBroker->acknowledgment);
        $this->rappiBroker->validated = intval($this->rappiBroker->validated);
		if(intval($this->rappiBroker->id) > 0){
            $this->updateRappi();
		}else{
			$this->storeRappi();
		}
	}

    public function storeRappi()
    {
        try {	
            $restaurant = $this->userRestaurant();
            $this->rappiBroker->restaurant_id = $restaurant->id;
            $this->rappiBroker->save();
			$this->simpleAlert('success', 'Integração RAPPI registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function updateRappi()
    {
        try {
            $this->rappiBroker->save();
			$this->simpleAlert('success', 'Integração RAPPI registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function deleteRappi()
    {
        try {
            $this->rappiBroker->delete();
			$this->simpleAlert('success', 'Integração RAPPI foi excluída.');
            $this->rappiBroker = new RappiBroker();
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar excluir o delivery.');
        }
    } 

}
