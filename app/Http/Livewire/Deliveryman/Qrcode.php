<?php

namespace App\Http\Livewire\Deliveryman;

use Livewire\Component;
use App\Actions\ProductionLine\GenerateTrackingOrdersQr;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Qrcode extends Component
{

    public $qrCodeUrl;
    public $restaurants;

    public function render()
    {
        $userId = auth()->user()->user_id ?? auth()->user()->id;
        $this->restaurants = implode(' &bull; ',  (new RecoverUserRestaurant())->recoverAll($userId)->pluck("name")->toArray());
        $this->qrCodeUrl = route('panels.public-delivery-panel.index', (new GenerateTrackingOrdersQr())->encode($userId));
        $viewName = 'livewire.deliveryman.qrcode';
        return view($viewName, [])->layout('layouts.public-clean');;        
    }
}
