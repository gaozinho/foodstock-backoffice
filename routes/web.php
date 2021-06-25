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
    Brokers, Restaurants, ProductionLines, WizardSuccess, Teams
};

use App\Http\Livewire\Panels\{
    ProductionLinePanel
};

Route::get('/wizard/restaurants', Restaurants::class)->name('wizard.restaurant.index')->middleware('auth');
Route::get('/wizard/brokers', Brokers::class)->name('wizard.broker.index')->middleware('auth');
Route::get('/wizard/production-lines', ProductionLines::class)->name('wizard.production-line.index')->middleware('auth');
Route::get('/wizard/success', WizardSuccess::class)->name('wizard.success.index')->middleware('auth');

Route::get('/configuration/restaurants', Restaurants::class)->name('configuration.restaurant.index')->middleware('auth');
Route::get('/configuration/brokers', Brokers::class)->name('configuration.broker.index')->middleware('auth');
Route::get('/configuration/production-lines', ProductionLines::class)->name('configuration.production-line.index')->middleware('auth');
Route::get('/configuration/teams', Teams::class)->name('configuration.teams.index')->middleware('auth');

Route::get('/panel/production-line/{role_name}', ProductionLinePanel::class)->name('panels.production-line-panel.index')->middleware('auth');

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

    $productionMovement = $startProductionProccess->start(113680);

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
    $orderBabel = new IfoodOrderBabel('{
       "id":"ce3c1467-df5d-4803-9016-867c491ee33c",
       "items":[
          {
             "id":"811e1dcf-26fb-4d11-b956-0bd2fb0f2f6d",
             "name":"PEDIDO DE TESTE - Sanduíche",
             "unit":"UN",
             "index":1,
             "price":150,
             "options":[
                {
                   "id":"e26fa3ef-88b2-462d-b91e-02090d3f89ef",
                   "name":"Complemento 1",
                   "unit":"UN",
                   "index":2,
                   "price":1,
                   "quantity":1,
                   "unitPrice":1
                },
                {
                   "id":"50b2596d-ef3e-4ea4-8b94-0581ea510a4f",
                   "name":"Complemento 2",
                   "unit":"UN",
                   "index":3,
                   "price":2,
                   "quantity":1,
                   "unitPrice":2
                },
                {
                   "id":"d740ae59-d74b-40cb-919c-b2d0e3eab5a4",
                   "name":"Complemento 6",
                   "unit":"UN",
                   "index":4,
                   "price":0,
                   "quantity":1,
                   "unitPrice":0
                },
                {
                   "id":"f13ae4b7-d0a1-4040-b525-860ffe9efa49",
                   "name":"Complemento 7",
                   "unit":"UN",
                   "index":5,
                   "price":0,
                   "quantity":1,
                   "unitPrice":0
                }
             ],
             "quantity":3,
             "unitPrice":50,
             "totalPrice":159,
             "externalCode":"c01-i001",
             "observations":"Tirar cebola. Eca!",
             "optionsPrice":9
          },
          {
             "id":"12f8bb8b-b595-49b0-b0cc-601d47587d52",
             "name":"PEDIDO DE TESTE - Bebida teste 100 ml",
             "unit":"UN",
             "index":6,
             "price":20,
             "options":[
                {
                   "id":"a6004347-a208-4b6c-a5fa-847798dfa0cd",
                   "name":"Laranja",
                   "unit":"UN",
                   "index":7,
                   "price":10,
                   "quantity":1,
                   "unitPrice":10
                }
             ],
             "quantity":2,
             "unitPrice":10,
             "totalPrice":40,
             "observations":"Enche bem o copo.",
             "optionsPrice":20
          },
          {
             "id":"9fd4241a-7d1c-4886-bf81-6df5ba403528",
             "name":"PEDIDO DE TESTE - Nome do Refrigerante 350 ml",
             "unit":"UN",
             "index":8,
             "price":5,
             "quantity":1,
             "unitPrice":5,
             "totalPrice":5,
             "observations":"Bem geladinho.",
             "optionsPrice":0
          },
          {
             "id":"9c1d2e6c-78e9-33b1-8031-819b6de81955",
             "name":"PEDIDO DE TESTE - GRANDE 2 SABORES (8 PEDAÇOS)",
             "unit":"UN",
             "index":9,
             "price":0,
             "options":[
                {
                   "id":"1b7e90e4-47a5-330e-8af7-59e48c8ace51",
                   "name":"Massa Tradicional + Borda Recheada",
                   "unit":"UN",
                   "index":10,
                   "price":3,
                   "quantity":1,
                   "unitPrice":3,
                   "externalCode":"m01"
                },
                {
                   "id":"4b26667a-8123-3ba8-be26-208d8abb0eca",
                   "name":"1/2 Portuguesa",
                   "unit":"UN",
                   "index":11,
                   "price":16,
                   "quantity":1,
                   "unitPrice":16,
                   "externalCode":"c02-i001"
                },
                {
                   "id":"8b134240-96e0-3602-9416-eaea87794643",
                   "name":"1/2 Calabresa",
                   "unit":"UN",
                   "index":12,
                   "price":15,
                   "quantity":1,
                   "unitPrice":15,
                   "externalCode":"c02-i002"
                }
             ],
             "quantity":3,
             "unitPrice":0,
             "totalPrice":105,
             "externalCode":"t03",
             "observations":"Capricha no queijo.",
             "optionsPrice":105
          }
       ],
       "total":{
          "benefits":0,
          "subTotal":309,
          "deliveryFee":8.9,
          "orderAmount":317.9,
          "additionalFees":0
       },
       "isTest":true,
       "customer":{
          "id":"22069c0b-8337-4ad7-993a-549eeb5c2acc",
          "name":"PEDIDO DE TESTE - Wagner Gomes Gonçalves",
          "phone":{
             "number":"0800 007 0110",
             "localizer":"47547653",
             "localizerExpiration":"2021-06-23T21:36:48.488156Z"
          },
          "ordersCountOnMerchant":0
       },
       "delivery":{
          "mode":"DEFAULT",
          "deliveredBy":"MERCHANT",
          "deliveryAddress":{
             "city":"Bujari",
             "state":"AC",
             "country":"BR",
             "postalCode":"00000000",
             "streetName":"PEDIDO DE TESTE - NÃO ENTREGAR - Ramal Bujari",
             "coordinates":{
                "latitude":-9.822159,
                "longitude":-67.948475
             },
             "neighborhood":"Bujari",
             "streetNumber":"100",
             "formattedAddress":"PEDIDO DE TESTE - NÃO ENTREGAR - Ramal Bujari, 100"
          },
          "deliveryDateTime":"2021-06-23T19:16:48.488156Z"
       },
       "merchant":{
          "id":"d18dd059-d9b2-4758-b97c-f8c506d80949",
          "name":"Teste - FoodStock"
       },
       "payments":{
          "methods":[
             {
                "cash":{
                   "changeFor":400
                },
                "type":"OFFLINE",
                "value":317.9,
                "method":"CASH",
                "prepaid":false,
                "currency":"BRL"
             }
          ],
          "pending":317.9,
          "prepaid":0
       },
       "createdAt":"2021-06-23T18:36:48.488156Z",
       "displayId":"9939",
       "orderType":"DELIVERY",
       "orderTiming":"IMMEDIATE",
       "salesChannel":"IFOOD",
       "preparationStartDateTime":"2021-06-23T18:36:48.488156Z"
    }');

dd($orderBabel->toJson());

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