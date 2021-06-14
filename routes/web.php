<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

use App\Http\Livewire\{
    Eventos
};

Route::get('/eventos', Eventos::class)->name('eventos.index')->middleware('auth');
Route::get('/eventos/edit', Eventos::class)->name('eventos.edit')->middleware('auth');

use App\Http\Livewire\Configuration\{
    Brokers, Restaurants, ProductionLines, WizardSuccess
};

Route::get('/wizard/restaurants', Restaurants::class)->name('wizard.restaurant.index')->middleware('auth');
Route::get('/wizard/brokers', Brokers::class)->name('wizard.broker.index')->middleware('auth');
Route::get('/wizard/production-lines', ProductionLines::class)->name('wizard.production-line.index')->middleware('auth');
Route::get('/wizard/success', WizardSuccess::class)->name('wizard.success.index')->middleware('auth');

Route::get('/configuration/restaurants', Restaurants::class)->name('configuration.restaurant.index')->middleware('auth');
Route::get('/configuration/brokers', Brokers::class)->name('configuration.broker.index')->middleware('auth');
Route::get('/configuration/production-lines', ProductionLines::class)->name('configuration.production-line.index')->middleware('auth');

//################### TESTES

use App\Integrations\IfoodIntegrationCentralized;
use App\Integrations\IfoodIntegrationDistributed;

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

});