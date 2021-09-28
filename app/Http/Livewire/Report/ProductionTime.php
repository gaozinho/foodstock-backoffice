<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use App\Actions\Report\ProductionSpentTimeReport;

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
        $this->productionSpentTime = new ProductionSpentTimeReport();
        return $this->productionSpentTime->displayPdfReport([61, 67, 66, 54, 62], $this->dateParse($date));
    }

    public function excel($date){
        $this->productionSpentTime = new ProductionSpentTimeReport();
        return $this->productionSpentTime->displayExcelReport([61, 67, 66, 54, 62], $this->dateParse($date));
    }    

    public function render()
    {
        return view('livewire.report.production-time');
    }
}
