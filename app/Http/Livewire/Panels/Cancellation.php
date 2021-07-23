<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\OrderSummary;
use App\Models\CancellationReason;
use App\Foodstock\Babel\OrderBabelized;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Events\CancellationRequested;
use App\Models\IfoodBroker;

class Cancellation extends Component
{
    public $orderSummaryId;
    public $cancellation_code = "501";
    public $orderSummaryDetail;
    public $restaurant;
    public $cancellation_requested = 0;

    public $ifoodCodes = [
        "501" => "PROBLEMAS DE SISTEMA",
        "502" => "PEDIDO EM DUPLICIDADE",
        "503" => "ITEM INDISPONÍVEL",
        "504" => "RESTAURANTE SEM MOTOBOY",
        "505" => "CARDÁPIO DESATUALIZADO",
        "505" => "PEDIDO FORA DA ÁREA DE ENTREGA",
        "507" => "CLIENTE GOLPISTA / TROTE",
        "508" => "FORA DO HORÁRIO DO DELIVERY",
        "509" => "DIFICULDADES INTERNAS DO RESTAURANTE",
        "511" => "ÁREA DE RISCO",
        "512" => "RESTAURANTE ABRIRÁ MAIS TARDE",
        "513" => "RESTAURANTE FECHOU MAIS CEDO",
    ];

    public function render()
    {
        return view('livewire.panels.cancellation');
    }

    public function mount($orderSummaryId){
        $this->orderSummaryId = $orderSummaryId;
        try{
            $this->restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            $this->orderSummaryDetail = $this->prepareOrderSummary($orderSummaryId);
        }catch(\Exception $e){
            session()->flash('error', 'Você não está vinculado a um delivery. Solicite ao responsável a sua correta vinculação.');
            return redirect()->route('dashboard');
        }        
    }

    public function cancellationRequest(){
        $this->orderSummaryDetail = $this->prepareOrderSummary($this->orderSummaryId);
        CancellationRequested::dispatch(
            $this->orderSummaryDetail->orderBabelized, 
            IfoodBroker::where("restaurant_id", $this->orderSummaryDetail->restaurant_id)->firstOrFail(),
            $this->ifoodCodes[$this->cancellation_code],
            $this->cancellation_code
        );

        $orderSummary = OrderSummary::find($this->orderSummaryId);
        $this->orderSummaryDetail->cancellation_requested = $orderSummary->cancellation_requested = 1;
        $orderSummary->save();

        $this->cancellation_requested = 1;

        $this->alert('success', 'A solicitação de cancelamento foi enviada. Aguarde confirmação do marketplace.', [
            'position' =>  'top-end', 
            'timer' =>  3000,  
            'toast' =>  true, 
            'text' =>  '', 
            'confirmButtonText' =>  'Ok', 
            'cancelButtonText' =>  'Cancel', 
            'showCancelButton' =>  false, 
            'showConfirmButton' =>  false, 
        ]);

        //$this->loadData();
        //$this->emit('closeOrderModal');
    }    

    private function prepareOrderSummary($order_summary_id){
        $orderSummary =  OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            ->where("order_summaries.id", $order_summary_id)
            ->where("order_summaries.restaurant_id", $this->restaurant->id)
            ->select(['order_summaries.*', 'production_movements.paused'])
            ->orderBy("production_movements.id", "desc")
            ->firstOrFail();
        $orderSummary->orderBabelized = new OrderBabelized($orderSummary->order_json);
        return $orderSummary;
    }    
}
