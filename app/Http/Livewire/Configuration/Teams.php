<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Role;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use Illuminate\Support\Facades\Hash;

class Teams extends Component
{

    public User $user;
    public $restaurantUsers;
    public $restaurant;
    public $roles;
    public $selectedRoles = [];

    protected $rules = [
        'user.name' => 'required|string|min:1|max:255',
        'user.email' => 'email|max:255|required|unique:users,email',
        'user.password' => 'min:4|max:8|required',
        'password_confirmation' => 'min:4|max:8|required|same:user.password',
    ];

    public $password_confirmation;

    protected $messages = [
        //'user.password.required' => 'O CNPJ informado é inválido.'
    ]; 

    public function mount()
    {
        //MVP 1 - Um restaurante por usuário
        $this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrNew();
        $this->user = new User();
        $this->roles = Role::where("guard_name", "production-line")->get();
        $this->restaurantUsers = $this->restaurant->usersPivot()->get();
    }    

    public function render()
    {
        $viewName = 'livewire.configuration.teams';
        return view($viewName, [])->layout('layouts.app', ['header' => 'Equipe de trabalho']);
    }

    public function loadUser($id){
        $this->user = User::findOrFail($id);
        $this->selectedRoles = $this->user->roles()->pluck("id", "id");
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();
        
		if(intval($this->user->id) > 0){
			$this->update();
		}else{
			$this->store();
		}
	}

    public function reloadForm(){
        $this->selectedRoles = [];
        $this->user = new User();
        $this->restaurantUsers = $this->restaurant->usersPivot()->get();
    }

    public function store()
    {
        try {	
            $this->user->password = Hash::make($this->user->password);
            $this->user->save();
            $selectedRoles = array_values(array_diff( $this->selectedRoles, [false]));
            $this->user->roles()->sync($selectedRoles);
            $this->restaurant->usersPivot()->attach($this->user);

            $this->reloadForm();
            
            $this->simpleAlert('success', 'Integrante adicionado com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o User.');
        }
    }

    public function destroy()
    {
        try {
            //$this->user->delete();
            $this->restaurant->usersPivot()->detach($this->user);
            $this->reloadForm();
			$this->simpleAlert('success', 'Integrante removido com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o User.');
        }
    } 

    public function update()
    {
        try {
            $this->user->password = Hash::make($this->user->password);
            $this->user->save();
            $selectedRoles = array_values(array_diff( $this->selectedRoles, [false]));
            $this->user->roles()->sync($selectedRoles);            
            $this->reloadForm();
			$this->simpleAlert('success', 'Integrante atualizado com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o User.');
        }
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
