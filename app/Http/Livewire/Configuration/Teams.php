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
    public $restaurantUsers = [];
    public $restaurants;
    public $roles;
    public $selectedRoles = [];
    public $selectedRestaurants = [];
    public $genericRoles = [];

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
        //if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        $this->user = new User();
        $this->loadData();
    }    

    public function loadData(){
        $user = auth()->user();
        $this->roles = Role::join("production_lines", "production_lines.role_id", "=", "roles.id")
            ->where("production_lines.user_id", auth()->user()->user_id ?? auth()->user()->id)
            ->where("production_lines.is_active", 1)
            //->where("production_lines.production_line_id", null)
            ->where("roles.guard_name", "production-line")
            ->select("roles.id", "production_lines.name")
            ->get();

        $this->genericRoles = Role::where("guard_name", "panel")->get();

        $this->restaurants =  (new RecoverUserRestaurant())->recoverAll($user->id); 
        $restaurantsIds =  (new RecoverUserRestaurant())->recoverAllIds($user->id); 
        $this->restaurantUsers = User::join("restaurant_has_users", "restaurant_has_users.user_id", "=", "users.id")
            ->where("users.restaurant_member", '1')
            ->whereIn("restaurant_has_users.restaurant_id", $restaurantsIds)
            ->selectRaw("distinct users.*")
            ->orderBy("users.name")
            ->get();        
    }

    public function render()
    {
        try{
            $this->loadData();
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
        $this->loadData();
        $this->user = User::findOrFail($id);
        $this->selectedRoles = $this->user->roles()->pluck("id", "id")->toArray();
        //dd($this->selectedRoles);
        $this->selectedRestaurants = $this->user->restaurants()->pluck("id", "id")->toArray();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->rules['user.email'] = 'max:255|required|unique:users,email,' . $this->user->id;
        
		if(intval($this->user->id) > 0){
			$this->update();
		}else{
			$this->store();
		}

        $this->loadData();
	}

    public function reloadForm(){
        $this->selectedRoles = [];
        $this->selectedRestaurants = [];
        $this->user = new User();
        //$this->restaurantUsers = $this->restaurant->usersPivot()->get();
    }

    public function store()
    {

        $this->validate();

        try {	
            $this->user->password = Hash::make($this->user->password);
            $this->user->restaurant_member = 1;
            $this->user->email_verified_at = date("Y-m-d H:i:s");
            $this->user->user_id = auth()->user()->user_id ?? auth()->user()->id;
            $this->user->save();
            $selectedRoles = array_values(array_diff( $this->selectedRoles, [false]));
            $selectedRestaurants = array_values(array_diff( $this->selectedRestaurants, [false]));
            
            $this->user->roles()->sync($selectedRoles);
            $this->user->restaurants()->sync($selectedRestaurants);

            //$this->restaurant->usersPivot()->attach($this->user);

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

            $restaurantsIds =  (new RecoverUserRestaurant())->recoverAllIds($user->user_id ?? $user->id); 
            if(count($restaurantsIds) > 0) $user->restaurants()->detach($restaurantsIds);
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
            $this->user->email_verified_at = date("Y-m-d H:i:s");
            unset($this->rules["user.password"]);
            unset($this->rules["password_confirmation"]);
        }

        $this->validate();

        if(trim($this->user->password) != ""){
            $this->user->password = Hash::make($this->user->password);
        }else{
            $this->user->password = $this->user->getRawOriginal('password');
        }

        try {

            $this->user->restaurant_member = 1;
            $this->user->user_id = auth()->user()->user_id ?? auth()->user()->id;
            $this->user->save();

            $selectedRoles = count($this->selectedRoles) > 0 ? array_values(array_diff( $this->selectedRoles, [false])) : [];
            $this->user->roles()->sync($selectedRoles);          
            $selectedRestaurants = count($this->selectedRestaurants) > 0 ? array_values(array_diff( $this->selectedRestaurants, [false])) : [];
            $this->user->restaurants()->sync($selectedRestaurants);
            
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
