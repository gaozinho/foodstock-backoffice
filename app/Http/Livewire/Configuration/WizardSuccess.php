<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class WizardSuccess extends BaseConfigurationComponent
{
    public function render()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        
        return view('livewire.configuration.wizard-success')
            ->layout('layouts.app');
    }
}
