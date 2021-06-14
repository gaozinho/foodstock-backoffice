<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Restaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class Restaurants extends BaseConfigurationComponent
{

    public Restaurant $restaurant;

    protected $rules = [
        'restaurant.name' => 'required|string|min:1|max:255',
        'restaurant.address' => 'max:255|required',
        'restaurant.cep' => 'formato_cep|size:10|required',
        'restaurant.cnpj' => 'cnpj|size:18|nullable',
        'restaurant.complement' => 'max:255|nullable',
        'restaurant.site' => 'url|max:500|nullable',
        'restaurant.email' => 'email|max:255|required',
        'restaurant.phone' => 'min:14|max:15|required',
    ];

    protected $messages = [
        'restaurant.cnpj.cnpj' => 'O CNPJ informado é inválido.'
    ]; 

    public function mount()
    {
        $this->wizardStep = 1;
        //MVP 1 - Um restaurante por usuário
        $this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrNew();
    }    

    public function render()
    {
        $viewName = 'livewire.configuration.restaurants';
        if($this->isWizard()) $viewName = 'livewire.configuration.wizard';

        return view($viewName, [])->layout('layouts.app', ['header' => 'Dados do delivery']);
    }

    public function save()
    {
        $this->validate();
		if(intval($this->restaurant->id) > 0){
			$this->update();
		}else{
			$this->store();
		}

        if($this->wizard) return redirect()->route('wizard.broker.index');
	}

    public function store()
    {
        try {	
            $this->restaurant->user_id = auth()->user()->id;
            $this->restaurant->save();
            $this->simpleAlert('success', 'Delivery atualizado com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o Restaurant.');
        }
    }

    public function update()
    {
        try {
            $this->restaurant->save();
			$this->simpleAlert('success', 'Delivery atualizado com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o Restaurant.');
        }
    }
}
