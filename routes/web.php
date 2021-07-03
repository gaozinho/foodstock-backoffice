<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify', function () {
   return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
   $request->fulfill();

   return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
   $request->user()->sendEmailVerificationNotification();

   return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

use App\Http\Livewire\Configuration\{
    Brokers, Restaurants, ProductionLines, WizardSuccess, Teams
};

use App\Http\Livewire\Panels\{
   ProductionLinePanel, DeliveryPanel
};

use App\Http\Livewire\Deliveryman\DeliverymanPanel;
use App\Http\Livewire\Keyboard\NumericKeyboard;
use App\Http\Livewire\Dashboard\Welcome;
use App\Http\Livewire\Dashboard\Info;

Route::get('/home', Info::class)->name('dashboard')->middleware(['auth', 'verified']);
Route::get('/dashboard', Info::class)->name('dashboard')->middleware(['auth', 'verified']);
Route::get('/welcome', Welcome::class)->name('welcome')->middleware(['auth', 'verified']);

Route::group(['middleware' => ['role:admin', 'verified']], function (){
   Route::get('/wizard/restaurants', Restaurants::class)->name('wizard.restaurant.index')->middleware('auth');
   Route::get('/wizard/brokers', Brokers::class)->name('wizard.broker.index')->middleware('auth');
   Route::get('/wizard/production-lines', ProductionLines::class)->name('wizard.production-line.index')->middleware('auth');
   Route::get('/wizard/success', WizardSuccess::class)->name('wizard.success.index')->middleware('auth');
   
   Route::get('/configuration/restaurants', Restaurants::class)->name('configuration.restaurant.index')->middleware('auth');
   Route::get('/configuration/brokers', Brokers::class)->name('configuration.broker.index')->middleware('auth');
   Route::get('/configuration/production-lines', ProductionLines::class)->name('configuration.production-line.index')->middleware('auth');
   Route::get('/configuration/teams', Teams::class)->name('configuration.teams.index')->middleware('auth');
});

Route::get('/panel/production-line/{role_name}', ProductionLinePanel::class)->name('panels.production-line-panel.index')->middleware('auth');
Route::get('/panel/delivery', DeliveryPanel::class)->name('panels.delivery-panel.index')->middleware('auth');

Route::get('/panel/deliveryman/{restaurant_id}', DeliverymanPanel::class)->name('panels.public-delivery-panel.index');

Route::get('/order/keyboard', NumericKeyboard::class)->name('orders.keyboard.index')->middleware('auth');


//################### TESTES

use App\Integrations\RappiIntegration;
use App\Integrations\IfoodIntegrationCentralized;
use App\Integrations\IfoodIntegrationDistributed;

use App\Actions\ProductionLine\StartProductionProccess;
use App\Actions\ProductionLine\ForwardProductionProccess;
use App\Actions\ProductionLine\RecoveryOrders;

Route::get('/production', function () {

    $startProductionProccess = new StartProductionProccess();
    //$forwardProductionProccess = new ForwardProductionProccess();

    $productionMovement = $startProductionProccess->start(113688);

    dd($productionMovement);

    $productionMovement = $startProductionProccess->start(113667);
    $productionMovement = $forwardProductionProccess->forward(113667, 2);

    $productionMovement = $startProductionProccess->start(113668);
    $productionMovement = $forwardProductionProccess->forward(113668, 2);
    $productionMovement = $forwardProductionProccess->forward(113668, 2);

    $productionMovement = $startProductionProccess->start(113660);
    $productionMovement = $forwardProductionProccess->forward(113660, 2);
    $productionMovement = $forwardProductionProccess->forward(113660, 2);    
    $productionMovement = $forwardProductionProccess->forward(113660, 2);    

    $productionMovement = $startProductionProccess->start(113661);
    $productionMovement = $forwardProductionProccess->forward(113661, 2);
    $productionMovement = $forwardProductionProccess->forward(113661, 2);    
    $productionMovement = $forwardProductionProccess->forward(113661, 2);   
    $productionMovement = $forwardProductionProccess->forward(113661, 2);        
});


use App\Foodstock\Babel\IfoodOrderBabel;

Route::get('/order-babel', function () {
   $orderBabel = new IfoodOrderBabel('');

   //dd($orderBabel->toJson());

});

Route::get('/integrations', function () {

    //LOGIN CENTRALIZADO
    //$IfoodIntegration = new IfoodIntegrationCentralized();
    //$token = $IfoodIntegration->getToken();
    //$merchant = $IfoodIntegration->getMerchant("d18dd059-d9b2-4758-b97c-f8c506d80949");
    //dd($merchant);

    //LOGIN DISTRIBUIDO
    //$IfoodIntegration = new IfoodIntegrationDistributed();
    //$usercode = $IfoodIntegration->getUserCode();
    //$ifoodBroker = $IfoodIntegration->associateUserCodeAndBroker($usercode, 2);
    //$token = $IfoodIntegration->getToken('KGLD-NKVR', 2);
    //$merchants = $IfoodIntegration->getMerchants(2);
    //dd($merchants);

    //RAPPI
    //$rappiIntegration = new RappiIntegration();
    //$token = $rappiIntegration->getToken(3);

});