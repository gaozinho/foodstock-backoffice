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
use App\Actions\ProductionLine\FinishOrder;
use App\Models\ProductionMovement;

use App\Foodstock\Babel\OrderBabelized;

class ProductionLinePanel extends Component
{

    protected $listeners = ['loadData', 'finishOrders', 'cancelOperation', 'batchFinish'];

    public $orderSummaries;
    public $orderSummariesPreviousStep;
    public $productionLine;
    public $productionLineCanPause;
    public $orderProductionLine;
    public $stepColors;
    public $legends;
    public $orderSummaryDetail;
    public $selectedOrderIds = [];
    public $selectable = false;

    public $restaurantIds;
    public $recoveryOrders;
    public $role_name;
    public $total_orders = 0;
    public $cancellation_code = "501";
    public $production_line_id = 0; //A tela estava apontada neste passo

    //public $orderInfo = false;

    public $orderBabelized;

    protected $messages = [
        //'user.password.required' => 'O CNPJ informado é inválido.'
    ]; 

    public function getUserName($id){
        $user = User::find($id);
        if(is_object($user)){
            return $user->name;
        }
        return "Indefinido";
    }

    public function mount($role_name)
    {

        //Tenta membro de restaurante e depois o dono
        $userId = auth()->user()->user_id ?? auth()->user()->id;

        if(!(auth()->user()->hasRole("admin") || auth()->user()->hasRole($role_name))){
            return redirect()->to('/dashboard');
        }

        $this->role_name = $role_name;
        $this->orderSummaryDetail = new OrderSummary();
        $this->orderSummaryDetail->orderBabelized = new OrderBabelized('{}');

        //MVP 1 - Um restaurante por usuário

        try{
            $this->restaurantIds = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();
        }catch(\Exception $e){
            session()->flash('error', 'Você não está vinculado a um delivery. Solicite ao responsável a sua correta vinculação.');
            return redirect()->route('dashboard');
        }

        //$this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        $recoveryOrders = new RecoveryOrders();
        
        $this->productionLine = $recoveryOrders->getCurrentProductionLineByRoleName($userId, $role_name);
        $this->productionLineCanPause = $this->canPause($this->productionLine);

        if($role_name != $this->productionLine->role->name){
            return redirect()->route('panels.production-line-panel.index', $this->productionLine->role->name);
        }

        $this->stepColors = $recoveryOrders->getProductionLineColors($userId);
        $this->legends = $this->getLegend($userId, $role_name);
        
        $this->loadData();
    }

    private function canPause($productionLine){
        return $productionLine->can_pause == 1 || ProductionLine::where("production_line_id", $productionLine->id)->count() > 0;
    }

    public function loadData(){
        $userId = auth()->user()->user_id ?? auth()->user()->id;
        
        $restaurantIds = (new RecoverUserRestaurant())->recoverAllIds($userId)->toArray();
        $recoveryOrders = new RecoveryOrders();
        $this->orderSummaries = $recoveryOrders->recoveryByRoleName($restaurantIds, $userId, $this->role_name);
        $this->orderSummariesPreviousStep = $recoveryOrders->recoveryPreviousByRoleName($userId, $this->role_name); 
        $this->total_orders = count($this->orderSummaries) + count($this->orderSummariesPreviousStep);
    }

    public function getLegend($user_id, $role_name){

        $role = Role::where("name", $role_name)->where("guard_name", "production-line")->firstOrFail();  
        $previousProductionLine = null;

        $currentProductionLine = ProductionLine::where("user_id", $user_id)
            ->where("is_active", 1)
            ->where("role_id", $role->id)
            ->where("production_line_id", null)
            ->firstOrFail();

        //Se see_previous, pega cor anterior
        if($currentProductionLine->see_previous){
            $previousProductionLine = ProductionLine::where("user_id", $user_id)
                ->where("is_active", 1)
                ->where("step", ($currentProductionLine->step - 1))
                ->where("production_line_id", null)
                ->firstOrFail();
        }
        //Se tem filhos, pega cor dos filhos
        $nextProductionLines = ProductionLine::where("user_id", $user_id)
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
            ->whereIn("order_summaries.restaurant_id", $this->restaurantIds)
            ->select(['order_summaries.*', 'production_movements.paused', 'production_movements.paused_by'])
            ->orderBy("production_movements.id", "desc")
            ->firstOrFail();
        $orderSummary->orderBabelized = new OrderBabelized($orderSummary->order_json);
        return $orderSummary;
    }

    public function finishOrders(){
        //$this->emit('loadingData');
        $restaurant_ids = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();
        $finishOrder = new FinishOrder();
        $finishOrder->finish($restaurant_ids, auth()->user()->id);
        $this->loadData();
        $this->emit('moveForward');
        $this->alert("success", "Todos os pedidos foram finalizados com sucesso.", ['timer' =>  5000]);        
    }

    public function cancelOperation(){
        $this->loadData();
        $this->emit('moveForward');
    }

    public function confirmFinishOrders()
    {
        $this->confirm('Deseja finalizar todos os pedidos?', [
            'toast' => false,
            'position' => 'center',
            'text' => 'Esta operação não pode ser desfeita.',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
            'onConfirmed' => 'batchFinish',
            'onCancelled' => 'cancelOperation'
        ]);
        $this->loadData();
    }    

    public function orderDetailAndMoveForward($order_summary_id, $production_line_id = null){
        $userId = auth()->user()->user_id ?? auth()->user()->id;
        $currentStep = $this->productionLine->step;
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
        
        //(new ForwardProductionProccess())->forward($this->orderSummaryDetail->order_id, $userId, $production_line_id);

        $forwardProductionProccess = new ForwardProductionProccess();
        //Valida se o pedido ainda está neste passo
        $currentMovement = $forwardProductionProccess->getCurrentMovementByOrderId($this->orderSummaryDetail->order_id);
        if(intval($production_line_id) > 0 && $production_line_id != $currentMovement->production_line_id){
            //dd("Mexeram no meu queijo.");
            $productionMovement = ProductionMovement::where("order_id", $this->orderSummaryDetail->order_id)
                ->where("production_line_id", $production_line_id)
                ->first();
            $this->alert("error", sprintf("A etapa %s já foi concluída por %s.", $productionMovement->productionLine->name, (is_object($productionMovement->finishedBy) ? $productionMovement->finishedBy->name : 'Sistema')), ['timer' =>  8000]);  
        }else{
            $nextProductionLineStep = $forwardProductionProccess->nextStep($userId, $currentStep);
            if(is_object($nextProductionLineStep)){
                $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
                do{
                    $productionMovement = $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $userId);
                }while($productionMovement->current_step_number < $nextProductionLineStep->step);
            }
        }

        $this->emit('openOrderModal');
        $this->loadData();
    }

    public function moveForwardFromCurrentStep($order_summary_id, $production_line_id = null){
        $userId = auth()->user()->user_id ?? auth()->user()->id;
        $currentStep = $this->productionLine->step;
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
        $forwardProductionProccess = new ForwardProductionProccess();



        //Valida se o pedido ainda está neste passo
        $currentMovement = $forwardProductionProccess->getCurrentMovementByOrderId($this->orderSummaryDetail->order_id);
        if(intval($production_line_id) > 0 && $production_line_id != $currentMovement->production_line_id){
            //dd("Mexeram no meu queijo.");
            $productionMovement = ProductionMovement::where("order_id", $this->orderSummaryDetail->order_id)
                ->where("production_line_id", $production_line_id)
                ->first();
            $this->alert("error", sprintf("A etapa %s já foi concluída por %s.", $productionMovement->productionLine->name, (is_object($productionMovement->finishedBy) ? $productionMovement->finishedBy->name : 'Sistema')), ['timer' =>  8000]);  
        }else{
            $nextProductionLineStep = $forwardProductionProccess->nextStep($userId, $currentStep);
            if(is_object($nextProductionLineStep)){
                $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
                do{
                    $productionMovement = $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $userId);
                }while($productionMovement->current_step_number < $nextProductionLineStep->step);
            }
        }


        
        $this->emit('moveForward');
        $this->loadData();
    }    

    public function orderDetail($order_summary_id, $production_line_id){

        $this->production_line_id = $production_line_id; //Armazena o passo onde estava
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
        $this->orderProductionLine = ProductionLine::findOrFail($production_line_id);
        
        if($this->orderProductionLine->next_on_click == 1){ //Se passa para próximo passo no clique
            (new ForwardProductionProccess())->forward($this->orderSummaryDetail->order_id, $this->orderProductionLine->user_id, $production_line_id);
            $this->emit('moveForward');
        }else{
            $this->emit('openOrderModal');
        }
        
        $this->loadData();
    }

    public function batchFinish(){
        $user_id = auth()->user()->user_id ?? auth()->user()->id;
        $finishOrder = new FinishOrder();
        
        $totalSuccess = $finishOrder->batchFinish($user_id, $this->selectedOrderIds);

        if($totalSuccess > 0){
            $this->alert("success", sprintf("%s pedidos finalizados.", $totalSuccess), ['timer' =>  5000]);              
        }else{
            $this->alert("error", sprintf("Nenhum pedido selecionado."), ['timer' =>  5000]);  
        }

        $this->selectedOrderIds = [];
        $this->loadData();
        $this->emit('moveForward');
    }

    public function batchNextStep(){
        $user_id = auth()->user()->user_id ?? auth()->user()->id;
        $forwardProductionProccess = new ForwardProductionProccess();

        if(is_array($this->selectedOrderIds) && count($this->selectedOrderIds) > 0){
            foreach($this->selectedOrderIds as $order_id){
                $forwardProductionProccess->forward($order_id, $user_id, $this->productionLine);
            }
            $this->alert("success", sprintf("%s pedidos avançaram para a próxima etapa.", count($this->selectedOrderIds)), ['timer' =>  5000]);              
        }else{
            $this->alert("error", sprintf("Nenhum pedido selecionado."), ['timer' =>  5000]);  
        }
        $this->selectedOrderIds = [];
        $this->loadData();
        $this->emit('moveForward');
    }

    //Pula um passo no processo
    public function nextStep($order_summary_id){
        $userId = auth()->user()->user_id ?? auth()->user()->id;
        $forwardProductionProccess = new ForwardProductionProccess();

        //Valida se o pedido ainda está neste passo
        $currentMovement = $forwardProductionProccess->getCurrentMovementByOrderId($this->orderSummaryDetail->order_id);

        if(intval($this->production_line_id) > 0 && $this->production_line_id != $currentMovement->production_line_id){
            //dd("Mexeram no meu queijo.");
            $productionMovement = ProductionMovement::where("order_id", $this->orderSummaryDetail->order_id)
                ->where("production_line_id", $this->production_line_id)
                ->first();

            $this->alert("error", sprintf("A etapa %s já foi concluída por %s.", $productionMovement->productionLine->name, (is_object($productionMovement->finishedBy) ? $productionMovement->finishedBy->name : 'Sistema')), ['timer' =>  8000]);  
        }else{
            $forwardProductionProccess->forward($this->orderSummaryDetail->order_id, $userId);
        }

        
        $this->loadData();
        $this->emit('closeOrderModal');
    }

    public function isWhereItShouldBe($order_id, $current_production_line_id, $real_production_line_id){
        //Valida se o pedido ainda está neste passo
        $forwardProductionProccess = new ForwardProductionProccess();
        $currentMovement = $forwardProductionProccess->getCurrentMovementByOrderId($order_id);
        if(intval($real_production_line_id) > 0 && $real_production_line_id != $current_production_line_id){
            //dd("Mexeram no meu queijo.");
            $productionMovement = ProductionMovement::where("order_id", $order_id)
                ->where("production_line_id", $real_production_line_id)
                ->first();
            $this->alert("error", sprintf("A etapa %s já foi concluída por %s.", $productionMovement->productionLine->name, $productionMovement->user->name), ['timer' =>  8000]);  
        }
    }

    public function pause($order_summary_id){
        $pauseProductionProccess = new PauseProductionProccess();
        $pauseProductionProccess->pause($this->orderSummaryDetail->order_id, auth()->user()->id);
        $this->loadData();
        $this->emit('closeOrderModal');
    }

    public function selectOrders(){
        $this->selectable = !$this->selectable;
        $this->loadData();
        $this->emit('moveForward');
    }

    public function render()
    {
        $viewName = 'livewire.panels.production-line';
        return view($viewName, []);
    }

    public function showInfo(){
        //$this->orderInfo = true;
    }

}
