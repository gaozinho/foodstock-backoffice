<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class WizardSuccess extends BaseConfigurationComponent
{
    public function render()
    {
        return view('livewire.configuration.wizard-success')
            ->layout('layouts.app', ['header' => 'Pronto! Seu delivery está configurado.']);;
    }
}
