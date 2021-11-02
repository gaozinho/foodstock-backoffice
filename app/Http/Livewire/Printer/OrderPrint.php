<?php

namespace App\Http\Livewire\Printer;

use Livewire\Component;
use App\Models\Printer;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class OrderPrint extends BaseConfigurationComponent
{

    public $printerName = '';
    public $printer = null;

    protected $rules = [
        'printerName' => 'max:255',
    ];    

    public function render()
    {
        return view('livewire.printer.order-print');
    }

    public function mount(){
        $this->printer = Printer::where('user_id', (auth()->user()->user_id ?? auth()->user()->id))->first();  
        if(is_object($this->printer)) $this->printerName = $this->printer->name;    

    }

    public function updatedPrinterName($value)
    {

        if(strlen($value) > 0){
            $this->printer = Printer::updateOrCreate(
                ['user_id' => (auth()->user()->user_id ?? auth()->user()->id)],
                ['name' => $value, 'available' => 1]
            );

            if(is_object($this->printer)){
                $this->printerName = $this->printer->name; 
                $this->simpleAlert('success', 'Impressora configurada com sucesso!');
            }else{
                $this->simpleAlert('error', 'Não conseguimos definir a impressora.');
            }
        }else{
            Printer::where('user_id', (auth()->user()->user_id ?? auth()->user()->id))->delete();
            $this->printer = null;
        }



        return redirect(request()->header('Referer'));
    }

    public function  reloadData(){
        $this->printer = Printer::where('user_id', (auth()->user()->user_id ?? auth()->user()->id))->first();  

    }
}
