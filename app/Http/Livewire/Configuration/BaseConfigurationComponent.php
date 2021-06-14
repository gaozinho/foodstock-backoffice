<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;

class BaseConfigurationComponent extends Component
{

    public $wizard = false;
    public $wizardStep = 1;

    function __construct() {
        //Está em tela wizard?
        $this->wizard = request()->is('wizard/*');
    }

    public function continue($next){
        return redirect()->route($next);
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

    public function isWizard(){
        return $this->wizard;
    }
}