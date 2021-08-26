<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Restaurant;
use App\Models\IfoodBroker;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use Illuminate\Database\Eloquent\Collection;

class Restaurants extends BaseConfigurationComponent
{

    public $restaurants;
    public $restaurantsCount = 0;
    public $id_restaurant;
    protected $listeners = ['loadData', 'disable', 'confirmDestroy'];

    public function mount()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        
        $this->wizardStep = 1;
        //MVP 1 - Um restaurante por usuário
        $this->loadData();
        //$this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrNew();
    }

    public function loadData(){
        $this->restaurants =  (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);
        $this->restaurantsCount = count($this->restaurants);
        
    }

    public function render()
    {
        $viewName = 'livewire.configuration.restaurants';
        if($this->isWizard()) $viewName = 'livewire.configuration.wizard';

        return view($viewName, [])->layout('layouts.app', ['header' => 'Sobre seu delivery']);
    }

    public function createRestaurant(){
        $restaurants =  (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);
        $this->restaurantsCount = count($restaurants);
        $restaurants->prepend((new Restaurant())->setConnection('mysql'));
        $this->restaurants = $restaurants;
        $this->emit('mountPageComponents');
    }

    public function disable(){
        try{
            $restaurant = Restaurant::findOrFail($this->id_restaurant);
            $restaurant->enabled = 0;
            $restaurant->save();
            IfoodBroker::where("restaurant_id", $restaurant->id)->delete();
            $this->simpleAlert('success', 'Loja excluída com sucesso.');
            $this->loadData();
        }catch(\Exception $e){
            $this->simpleAlert('error', 'Não conseguimos escluir a loja.');
        }

    }

    public function confirmDestroy($id_restaurant)
    {
        $this->id_restaurant = $id_restaurant;
        $this->confirm('Deseja excluir ' . Restaurant::findOrFail($id_restaurant)->name . '?', [
            'text' => 'Esta operação não pode ser desfeita. Todos os pedidos deste restaurante serão excluídos.',
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
            'onConfirmed' => 'disable'
        ]);
    }

}
