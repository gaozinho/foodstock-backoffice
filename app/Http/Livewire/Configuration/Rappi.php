<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Broker;
use App\Models\Restaurant;
use App\Models\RappiBroker;
use App\Enums\BrokerType;

class Rappi extends Component
{
    public RappiBroker $rappiBroker;
    public $rappi;

    public $brokerAction;

    protected $rules = [
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
            $this->rappiBroker = RappiBroker::where("enabled", "=", 1)->where("restaurant_id", "=", $restaurant->id)->firstOrNew();
        }catch(\Exception $exception){
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Antes de configurar os brokers, é necessário configurar seu delivery.');
        }
    }    

    public function render()
    {
        $this->rappi = Broker::where("enabled", "=", 1)
            ->where("id", "=", BrokerType::Rappi)
            ->first();

        $viewName = 'livewire.configuration.rappi';
        return view($viewName);
    }

    public function save()
    {

        $this->brokerAction = BrokerType::Rappi;

        $this->validate();

        $this->rappiBroker->broker_id = BrokerType::Rappi;
        $this->rappiBroker->enabled = intval($this->rappiBroker->enabled);
        $this->rappiBroker->acknowledgment = intval($this->rappiBroker->acknowledgment);
        $this->rappiBroker->validated = intval($this->rappiBroker->validated);
		if(intval($this->rappiBroker->id) > 0){
            $this->update();
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

    public function update()
    {
        try {
            $this->rappiBroker->save();
			$this->simpleAlert('success', 'Integração RAPPI registrada com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o delivery.');
        }
    }

    public function delete()
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
