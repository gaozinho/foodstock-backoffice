<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Broker;
use App\Models\Restaurant;
use App\Models\IfoodBroker;
use App\Enums\BrokerType;
use App\Integrations\IfoodIntegration;
use App\Integrations\IfoodIntegrationDistributed;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Ifood extends Component
{
    public IfoodBroker $ifoodBroker;
    public $restaurant;
    public $opened = false;

    protected $listeners = ['copied' => 'copiedAlert', 'regenerateCode' => 'generateIfoodCode'];

    public $ifood;

    public $brokerAction;

    protected $rules = [
        //'ifoodBroker.broker_id' => 'numeric|required',
        //'ifoodBroker.merchant_id' => 'max:255|required',
        //'ifoodBroker.client_secret' => 'max:255|required',
        //'ifoodBroker.username' => 'max:255|required',
        //'ifoodBroker.password' => 'max:255|required',
        'ifoodBroker.conclude' => 'boolean|nullable',
        'ifoodBroker.enabled' => 'boolean|nullable',
        'ifoodBroker.acknowledgment' => 'boolean|nullable',
        'ifoodBroker.dispatch' => 'boolean|nullable',
        'ifoodBroker.authorizationCode' => 'nullable',     
    ];

    private function userRestaurant(){
        //return Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        return (new RecoverUserRestaurant())->recover(auth()->user()->id);
    }

    public function mount()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        
        try{
            //MVP 1 - Um ifoodBrokere por usuário
            //$restaurant = $this->userRestaurant();
            $this->ifoodBroker = IfoodBroker::where("restaurant_id", "=", $this->restaurant->id)
                ->firstOrNew();
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
        $viewName = 'livewire.configuration.ifood';
        return view($viewName);
    }

    public function save($sendMessage = true)
    {
        $this->brokerAction = BrokerType::Ifood;

        $this->validate();

        $this->ifoodBroker->broker_id = BrokerType::Ifood;
		if(intval($this->ifoodBroker->id) > 0){
            $this->update($sendMessage);
		}else{
			$this->storeIfood($sendMessage);
		}
	}

    public function storeIfood($sendMessage = true)
    {
        try {	
            //$restaurant = $this->userRestaurant();
            $this->ifoodBroker->restaurant_id = $this->restaurant->id;
            $this->ifoodBroker->save();
			if($sendMessage) $this->simpleAlert('success', 'Integração IFOOD registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function update($sendMessage = true)
    {
        try {
            $this->ifoodBroker->save();
			if($sendMessage) $this->simpleAlert('success', 'Integração IFOOD registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function delete()
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
            $this->ifoodBroker->dispatch = 1;
            $this->ifoodBroker->conclude = 1;
            $this->ifoodBroker->userCode = $responseUserCode->userCode;
            $this->ifoodBroker->verificationUrlComplete = $responseUserCode->verificationUrlComplete;
            $this->save(false);
            $this->ifoodBroker = $IfoodIntegration->associateUserCodeAndBroker($responseUserCode, $this->ifoodBroker->id);
            $this->simpleAlert('success', 'Código gerado com sucesso.');
            $this->dispatchBrowserEvent('reloadCountdown', ['time' => $this->ifoodBroker->usercode_expires]);
        }
        $this->opened = true;
    }

    public function validateIfoodCode(){
        //$restaurant = $this->userRestaurant();
        $IfoodIntegration = new IfoodIntegrationDistributed();
        try{
            $IfoodIntegration->getToken($this->ifoodBroker->authorizationCode, $this->ifoodBroker->id);
            $jsonMerchants = $IfoodIntegration->getMerchants($this->ifoodBroker->id);

            //dd($jsonMerchants);


            //TODO - O QUE ACONTECE SE RETORNA MAIS DEUM MERCHANT? TESTAR
            //Perguntar para usuário qual merchant, caso venha mais de dois
            if($jsonMerchants){
                $success = $IfoodIntegration->enableMerchant($this->ifoodBroker, $jsonMerchants);
            }

            if($success){
                $this->ifoodBroker = IfoodBroker::where("enabled", "=", 1)->where("restaurant_id", "=", $this->restaurant->id)->firstOrFail();
                $this->simpleAlert('success', 'Parabéns! Conseguimos integrar o FoodStock com o seu delivery.');
            }else{
                $this->simpleAlert('error', 'Não é possível prosseguir. Este restaurante do ifood já está vinculado a outro usuário.');
            }
    
        }catch(\Exception $e){
            if($e->getCode() == 401) $this->simpleAlert('error', 'O código informado é inválido. Tente reiniciar o processo e/ou verifique se os códigos estão corretos e não expirados.');
            else if($e->getCode() == 500) $this->simpleAlert('error', 'O código informado é inválido. Verifique o formato infomado. Ex: A01A-AAAA');
            else $this->simpleAlert('error', 'Ocorreu um erro desconhecido. Tente novamente mais tarde.');
        }
        $this->opened = true;
    }

    public function copiedAlert($code)
    {
        $this->simpleAlert(
            'success',
            'Código ' . $code . ' copiado com sucesso!'
        );
    }    

    public function simpleAlert($type, $message){
        $this->alert($type, $message, [
            'position' =>  'top-end', 
            'timer' =>  3000,  
            'toast' =>  true, 
            'text' =>  '', 
            'confirmButtonText' =>  'Ok', 
            'cancelButtonText' =>  'Cancel', 
            'showCancelButton' =>  false, 
            'showConfirmButton' =>  false, 
        ]);
    }    
}
