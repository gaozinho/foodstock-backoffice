<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\ProductionLine;
use App\Models\OrderSummary;
use App\Actions\ProductionLine\RecoveryOrders;
use App\Actions\ProductionLine\ForwardProductionProccess;

class ProductionLinePanel extends Component
{

    public $orderSummaries;
    public $orderSummariesPreviousStep;
    public $productionLine;
    public $stepColors;
    public $legends;
    public $orderSummaryDetail;

    public $restaurant;
    public $recoveryOrders;
    public $role_name;
    public $total_orders = 0;

    protected $messages = [
        //'user.password.required' => 'O CNPJ informado é inválido.'
    ]; 

    public function mount($role_name)
    {
        $this->role_name = $role_name;
        $this->orderSummaryDetail = new OrderSummary();

        //MVP 1 - Um restaurante por usuário
        $this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        $recoveryOrders = new RecoveryOrders();
        $this->productionLine = $recoveryOrders->getCurrentProductionLineByRoleName($this->restaurant->id, $role_name);
        $this->stepColors = $recoveryOrders->getProductionLineColors($this->restaurant->id);
        $this->legends = $this->getLegend($this->restaurant->id, $role_name);
        $this->loadData();
    }    

    public function loadData(){
        $recoveryOrders = new RecoveryOrders();
        $this->orderSummaries = $recoveryOrders->recoveryByRoleName($this->restaurant->id, $this->role_name);
        $this->orderSummariesPreviousStep = $recoveryOrders->recoveryPreviousByRoleName($this->restaurant->id, $this->role_name); 
        $this->total_orders = count($this->orderSummaries) + count($this->orderSummariesPreviousStep);
    }

    public function getLegend($restaurant_id, $role_name){

        $role = Role::where("name", $role_name)->where("guard_name", "production-line")->firstOrFail();  
        $previousProductionLine = null;

        $currentProductionLine = ProductionLine::where("restaurant_id", $restaurant_id)
            ->where("is_active", 1)
            ->where("role_id", $role->id)
            ->where("production_line_id", null)
            ->firstOrFail();

        //Se see_previous, pega cor anterior
        if($currentProductionLine->see_previous){
            $previousProductionLine = ProductionLine::where("restaurant_id", $restaurant_id)
                ->where("is_active", 1)
                ->where("step", ($currentProductionLine->step - 1))
                ->where("production_line_id", null)
                ->firstOrFail();
        }
        //Se tem filhos, pega cor dos filhos
        $nextProductionLines = ProductionLine::where("restaurant_id", $restaurant_id)
            ->where("is_active", 1)
            ->where("production_line_id", $currentProductionLine->id)->get();
        
        $legend = [];
        if(is_object($previousProductionLine)) $legend[$previousProductionLine->step] = ["color" => $previousProductionLine->color, "name" => $previousProductionLine->name, "order" => "previous"];
        $legend[$currentProductionLine->step] = ["color" => $currentProductionLine->color , "name" => $currentProductionLine->name, "order" => "current"];
        if(count($nextProductionLines) > 0) foreach($nextProductionLines as $nextProductionLine) $legend[$nextProductionLine->step] = ["color" => $nextProductionLine->color, "name" => $nextProductionLine->name, "order" => "next"];

        return $legend;
    }

    public function orderDetailAndMoveForward($order_summary_id){
        $this->orderSummaryDetail = OrderSummary::findOrFail($order_summary_id);
        $forwardProductionProccess = new ForwardProductionProccess();
        $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $this->restaurant->id);
        $this->emit('openOrderModal');
        $this->loadData();
    }

    public function orderDetail($order_summary_id, $production_line_id){
        $this->orderSummaryDetail = OrderSummary::findOrFail($order_summary_id);
        $productionLine = ProductionLine::findOrFail($production_line_id);

        if($productionLine->next_on_click == 1){ //Se passa para próximo passo no clique
            $forwardProductionProccess = new ForwardProductionProccess();
            $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $this->restaurant->id);
        }else{
            $this->emit('openOrderModal');
        }
        $this->loadData();
    }

    public function nextStep($order_summary_id){
        $forwardProductionProccess = new ForwardProductionProccess();
        $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $this->restaurant->id);
        $this->loadData();
        $this->emit('closeOrderModal');
    }

    public function pause($order_summary_id){
        $this->loadData();
        $this->emit('closeOrderModal');
    }

    public function render()
    {
        $viewName = 'livewire.panels.production-line';
        return view($viewName, []);
    }

}