<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;

class BasePanel extends Component
{

    public $selectDate;

    protected $rules = [
        'selectDate' => 'nullable',
    ];
    
    public function mount(){
        $this->selectDate = date("d/m/Y");
    }

    public function render()
    {
        return view('livewire.report.base-panel');
    }
}
