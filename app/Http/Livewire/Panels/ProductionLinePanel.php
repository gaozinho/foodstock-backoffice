<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\IfoodBroker;
use App\Models\ProductionLine;
use App\Models\OrderSummary;
use App\Actions\ProductionLine\RecoveryOrders;
use App\Actions\ProductionLine\ForwardProductionProccess;
use App\Actions\ProductionLine\PauseProductionProccess;
use App\Actions\ProductionLine\RecoverUserRestaurant;

use App\Foodstock\Babel\OrderBabelized;

class ProductionLinePanel extends Component
{

    protected $listeners = ['loadData'];

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
    public $cancellation_code = "501";

    public $orderBabelized;

    protected $messages = [
        //'user.password.required' => 'O CNPJ informado é inválido.'
    ]; 

    public function mount($role_name)
    {

        if(!(auth()->user()->hasRole("admin") || auth()->user()->hasRole($role_name))){
            return redirect()->to('/dashboard');
        }

        $this->role_name = $role_name;
        $this->orderSummaryDetail = new OrderSummary();
        $this->orderSummaryDetail->orderBabelized = new OrderBabelized('{}');

        //MVP 1 - Um restaurante por usuário

        try{
            $this->restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        }catch(\Exception $e){
            session()->flash('error', 'Você não está vinculado a um delivery. Solicite ao responsável a sua correta vinculação.');
            return redirect()->route('dashboard');
        }

        //$this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
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

    private function prepareOrderSummary($order_summary_id){
        //$orderSummary = OrderSummary::findOrFail($order_summary_id);

        $orderSummary =  OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            ->where("order_summaries.id", $order_summary_id)
            ->where("order_summaries.restaurant_id", $this->restaurant->id)
            ->select(['order_summaries.*', 'production_movements.paused'])
            ->orderBy("production_movements.id", "desc")
            ->firstOrFail();
        $orderSummary->orderBabelized = new OrderBabelized($orderSummary->order_json);
        return $orderSummary;
    }

    public function orderDetailAndMoveForward($order_summary_id){
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
        (new ForwardProductionProccess())->forward($this->orderSummaryDetail->order_id, $this->restaurant->id);
        $this->emit('openOrderModal');
        $this->loadData();
    }

    public function moveForwardFromCurrentStep($order_summary_id){

        $currentStep = $this->productionLine->step;
        $forwardProductionProccess = new ForwardProductionProccess();
        $nextProductionLineStep = $forwardProductionProccess->nextStep($this->restaurant->id, $currentStep);

        if(is_object($nextProductionLineStep)){
            $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
            do{
                $productionMovement = $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $this->restaurant->id);
            }while($productionMovement->current_step_number < $nextProductionLineStep->step);
        }
        
        $this->emit('moveForward');
        $this->loadData();
    }    

    public function orderDetail($order_summary_id, $production_line_id){
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
        
        $productionLine = ProductionLine::findOrFail($production_line_id);
        
        if($productionLine->next_on_click == 1){ //Se passa para próximo passo no clique
            (new ForwardProductionProccess())->forward($this->orderSummaryDetail->order_id, $this->restaurant->id);
            $this->emit('moveForward');
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
        $pauseProductionProccess = new PauseProductionProccess();
        $pauseProductionProccess->pause($this->orderSummaryDetail->order_id, auth()->user()->id);
        $this->loadData();
        $this->emit('closeOrderModal');
    }

    public function render()
    {
        $viewName = 'livewire.panels.production-line';
        return view($viewName, []);
    }

}
