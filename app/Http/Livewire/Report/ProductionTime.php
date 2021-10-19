<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use App\Actions\Report\ProductionSpentTimeReport;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class ProductionTime extends Component
{

    private $productionSpentTime;
    public $selectDate;

    protected $rules = [
        'selectDate' => 'nullable',
    ];    

    public function mount(){
        
    }

    private function dateParse($date){
        $dateTime = \DateTime::createFromFormat("d/m/Y", $date);
        return $dateTime->format('Y-m-d');
    }

    public function pdf($date){
        $restaurant_ids = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();
        $this->productionSpentTime = new ProductionSpentTimeReport();
        return $this->productionSpentTime->displayPdfReport($restaurant_ids, $this->dateParse($date));
    }

    public function excel($date){
        $restaurant_ids = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();
        $this->productionSpentTime = new ProductionSpentTimeReport();
        return $this->productionSpentTime->displayExcelReport($restaurant_ids, $this->dateParse($date));
    }    

    public function render()
    {
        return view('livewire.report.production-time');
    }
}
