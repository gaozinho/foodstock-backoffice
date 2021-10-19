<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use App\Actions\Report\SalesDaysUserReport;

class SalesPerDayUsers extends Component
{

    private $salesDaysUser;
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
        $this->salesDaysUser = new SalesDaysUserReport();
        return $this->salesDaysUser->displayPdfReport(auth()->user()->user_id ?? auth()->user()->id, $this->dateParse($date));
    }

    public function excel($date){
        $this->salesDaysUser = new SalesDaysUserReport();
        return $this->salesDaysUser->displayExcelReport(auth()->user()->user_id ?? auth()->user()->id, $this->dateParse($date));
    }    

    public function render()
    {
        return view('livewire.report.sales-per-day-users');
    }
}
