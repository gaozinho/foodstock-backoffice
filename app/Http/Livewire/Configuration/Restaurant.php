<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Restaurant as RestaurantModel;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class Restaurant extends BaseConfigurationComponent
{

    public RestaurantModel $restaurant;
    public $index;
    

    protected $rules = [
        'restaurant.name' => 'required|string|min:1|max:255',
        'restaurant.address' => 'max:255|required',
        'restaurant.cep' => 'formato_cep|size:10|required',
        'restaurant.cnpj' => 'cnpj|size:18|nullable',
        'restaurant.complement' => 'max:255|nullable',
        'restaurant.site' => 'max:500|nullable',
        'restaurant.email' => 'email|max:255|required',
        'restaurant.phone' => 'min:14|max:15|required',
    ];

    protected $messages = [
        'restaurant.cnpj.cnpj' => 'O CNPJ informado é inválido.'
    ];     

    public function render()
    {
        return view('livewire.configuration.restaurant');
    }

    public function save()
    {
        $this->validate();
		if(intval($this->restaurant->id) > 0){
			$this->update();
		}else{
			$this->store();
		}

        //if($this->wizard) return redirect()->route('wizard.broker.index');
	}

    public function store()
    {
        try {	
            $this->restaurant->user_id = auth()->user()->id;
            $this->restaurant->save();
            $this->simpleAlert('success', 'Delivery atualizado com sucesso.');
        } catch (\Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o Restaurant.');
        }
    }

    public function update()
    {
        try {
            $this->restaurant->save();
			$this->simpleAlert('success', 'Delivery atualizado com sucesso.');
        } catch (\Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o Restaurant.');
        }
    }



}
