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
use App\Integrations\IfoodIntegrationDistributed;

class Brokers extends BaseConfigurationComponent
{
    public IfoodBroker $ifoodBroker;
    public RappiBroker $rappiBroker;

    protected $listeners = ['copied' => 'copiedAlert', 'regenerateCode' => 'generateIfoodCode'];

    public $ifood;
    public $rappi;

    public $brokerAction;

    protected $rules = [
        //'ifoodBroker.broker_id' => 'numeric|required',
        //'ifoodBroker.merchant_id' => 'max:255|required',
        //'ifoodBroker.client_secret' => 'max:255|required',
        //'ifoodBroker.username' => 'max:255|required',
        //'ifoodBroker.password' => 'max:255|required',
        'ifoodBroker.enabled' => 'boolean|nullable',
        'ifoodBroker.acknowledgment' => 'boolean|nullable',
        'ifoodBroker.authorizationCode' => 'nullable',

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

    public function saveIfood($sendMessage = true)
    {
        $this->brokerAction = BrokerType::Ifood;

        $this->validate();

        $this->ifoodBroker->broker_id = BrokerType::Ifood;
		if(intval($this->ifoodBroker->id) > 0){
            $this->updateIfood($sendMessage);
		}else{
			$this->storeIfood($sendMessage);
		}

        //Valida integração com IDCLIENTE
        //$integration = new IfoodIntegration();
        //$this->ifoodBroker->validated = $integration->getMerchant($this->ifoodBroker->merchant_id, true) == true;

	}

    public function storeIfood($sendMessage = true)
    {
        try {	
            $restaurant = $this->userRestaurant();
            $this->ifoodBroker->restaurant_id = $restaurant->id;
            $this->ifoodBroker->save();
			if($sendMessage) $this->simpleAlert('success', 'Integração IFOOD registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function updateIfood($sendMessage = true)
    {
        try {
            $this->ifoodBroker->save();
			if($sendMessage) $this->simpleAlert('success', 'Integração IFOOD registrada com sucesso.');
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

    public function generateIfoodCode(){
        $IfoodIntegration = new IfoodIntegrationDistributed();
        $responseUserCode = $IfoodIntegration->getUserCode();
        if($responseUserCode){
            $this->ifoodBroker->enabled = 1;
            $this->ifoodBroker->acknowledgment = 1;
            $this->ifoodBroker->userCode = $responseUserCode->userCode;
            $this->ifoodBroker->verificationUrlComplete = $responseUserCode->verificationUrlComplete;
            $this->saveIfood(false);
            $this->ifoodBroker = $IfoodIntegration->associateUserCodeAndBroker($responseUserCode, $this->ifoodBroker->id);
            $this->simpleAlert('success', 'Código gerado com sucesso.');
            $this->dispatchBrowserEvent('reloadCountdown', ['time' => $this->ifoodBroker->usercode_expires]);
        }
        //if($userCode) $this->ifoodBroker->userCode
        
        //dd($userCode);
    }

    public function validateIfoodCode(){
        $restaurant = $this->userRestaurant();
        $IfoodIntegration = new IfoodIntegrationDistributed();
        try{
            $IfoodIntegration->getToken($this->ifoodBroker->authorizationCode, $this->ifoodBroker->id);
            $IfoodIntegration->getMerchants($this->ifoodBroker->id);
            $this->ifoodBroker = IfoodBroker::where("enabled", "=", 1)->where("restaurant_id", "=", $restaurant->id)->firstOrFail();
            $this->simpleAlert('success', 'Parabéns! Conseguimos integrar o FoodStock com o seu delivery.');
    
        }catch(\Exception $e){
            if($e->getCode() == 401) $this->simpleAlert('error', 'O código informado é inválido. Verifique se os códigos estão corretos e não expirados.');
            else if($e->getCode() == 500) $this->simpleAlert('error', 'O código informado é inválido. Verifique o formato infomado. Ex: A01A-AAAA');
            else $this->simpleAlert('error', 'Ocorreu um erro desconhecido. Tente novamente mais tarde.');
        }
        

    }

    public function copiedAlert($code)
    {
        $this->simpleAlert(
            'success',
            'Código ' . $code . ' copiado com sucesso!'
        );
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
