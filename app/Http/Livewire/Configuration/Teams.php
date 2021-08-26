<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Role;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Teams extends Component
{

    public $user;
    public $restaurantUsers;
    public $restaurant;
    public $roles;
    public $selectedRoles = [];

    protected $rules = [
        'user.name' => 'required|string|min:1|max:255',
        'user.email' => 'max:255|required|unique:users,email',
        'user.password' => 'min:4|max:8|required',
        'password_confirmation' => 'min:4|max:8|required|same:user.password',
    ];

    public $password_confirmation;

    protected $messages = [
        //'user.password.required' => 'O CNPJ informado é inválido.'
    ]; 

    public function mount()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');

        //MVP 1 - Um restaurante por usuário
        $this->restaurant =  (new RecoverUserRestaurant())->recoverOrNew(auth()->user()->id);
        //$this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrNew();
        $this->user = new User();
        $this->roles = Role::join("production_lines", "production_lines.role_id", "=", "roles.id")
            ->where("production_lines.user_id", auth()->user()->id)
            ->where("production_lines.is_active", 1)
            ->where("roles.guard_name", "production-line")
            ->select("roles.id", "production_lines.name")
            ->get();
        $this->restaurantUsers = $this->restaurant->usersPivot()->get();
    }    

    public function render()
    {
        try{
            $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            $viewName = 'livewire.configuration.teams';
            return view($viewName, [])->layout('layouts.app', ['header' => 'Equipe de trabalho']);
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function hydrateUser(){
        $this->emit('stopLoading');
    }

    public function loadUser($id){
        $this->user = User::findOrFail($id);
        $this->selectedRoles = $this->user->roles()->pluck("id", "id")->toArray();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->rules['user.email'] = 'max:255|required|unique:users,email,' . $this->user->id;
        //$this->validate();
        
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

        $this->validate();

        try {	
            $this->user->password = Hash::make($this->user->password);
            $this->user->restaurant_member = 1;
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

            $user = User::findOrFail($this->user->id);
            $user->email = "DELETED-" . Str::random(10) . "-" . $this->user->email;
            $user->save();
            
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

        if(trim($this->user->password) == ""){
            $this->user->password = auth()->user()->password;
            unset($this->rules["user.password"]);
            unset($this->rules["password_confirmation"]);
        }else{
            $this->user->password = Hash::make($this->user->password);
        }

        $this->validate();

        try {
            $this->user->restaurant_member = 1;
            $this->user->save();
            $selectedRoles = count($this->selectedRoles) > 0 ? array_values(array_diff( $this->selectedRoles, [false])) : [];
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
