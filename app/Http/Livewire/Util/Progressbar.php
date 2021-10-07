<?php

namespace App\Http\Livewire\Util;

use Livewire\Component;

class Progressbar extends Component
{

    public $livewireListener;
    public $progressEnclosure;
    public $seconds;

    public function render()
    {
        return view('livewire.util.progressbar');
    }
}
