<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Actions\Report\ProductionSpentTimeReport;

Route::get('/report', function () {
   $productionSpentTime = new ProductionSpentTimeReport();

   //return $productionSpentTime->displayExcelReport([61, 67, 66, 54, 62], '2021-09-26');

   return $productionSpentTime->displayPdfReport([61, 67, 66, 54, 62], '2021-09-26');


});

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

use App\Http\Livewire\Products\{
   Products
};

use App\Http\Livewire\Deliveryman\DeliverymanPanel;
use App\Http\Livewire\Deliveryman\Qrcode;
use App\Http\Livewire\Keyboard\NumericKeyboard;
use App\Http\Livewire\Dashboard\Welcome;
use App\Http\Livewire\Dashboard\Info;
use App\Http\Livewire\Stock\Panel;

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
});

Route::group(['middleware' => ['role:admin|equipe', 'verified']], function (){
   Route::get('/configuration/teams', Teams::class)->name('configuration.teams.index')->middleware('auth');
});

Route::group(['middleware' => ['role:admin|produtos', 'verified']], function (){
   Route::get('/products', Products::class)->name('products.index')->middleware('auth');
   Route::get('/products/{id}', Products::class)->name('products.edit')->middleware('auth');
});

Route::group(['middleware' => ['role:admin|estoque', 'verified']], function (){
   Route::get('/stock/panel', Panel::class)->name('stock.panel')->middleware('auth');
});

Route::get('/panel/deliveryman/qrcode', Qrcode::class)->name('panels.public-delivery-qrcode.index')->middleware('auth');

Route::get('/panel/production-line/{role_name}', ProductionLinePanel::class)->name('panels.production-line-panel.index')->middleware('auth');
Route::get('/panel/delivery', DeliveryPanel::class)->name('panels.delivery-panel.index')->middleware('auth');
Route::get('/panel/deliveryman/{user_id}', DeliverymanPanel::class)->name('panels.public-delivery-panel.index');
Route::get('/order/keyboard', NumericKeyboard::class)->name('orders.keyboard.index')->middleware('auth');

/*

//################### TESTES

use App\Integrations\RappiIntegration;
use App\Integrations\IfoodIntegrationCentralized;
use App\Integrations\IfoodIntegrationDistributed;

use App\Actions\ProductionLine\StartProductionProccess;
use App\Actions\ProductionLine\ForwardProductionProccess;
use App\Actions\ProductionLine\RecoveryOrders;

use App\Actions\ProductionLine\GenerateOrderJson;
use App\Actions\Product\ProcessOrderProducts;
use App\Models\OrderSummary;
use App\Models\Order;

Route::get('/products/process', function () {
   $order = Order::findOrFail(118978);
   $generateOrderJson = new GenerateOrderJson($order);  
   $orderSummary = OrderSummary::find(5423);
   
   (new ProcessOrderProducts())->process($orderSummary, $generateOrderJson->babelizedOrder());
});

Route::get('/production', function () {

    $startProductionProccess = new StartProductionProccess();
    //$forwardProductionProccess = new ForwardProductionProccess();

    $productionMovement = $startProductionProccess->start(114822);

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
use App\Foodstock\Babel\OrderBabelized;

Route::get('/order-babel', function () {
   $orderBabel = new IfoodOrderBabel('{
      "id":"5ac73671-17a5-4c54-915f-b04e32d27f09",
      "items":[
         {
            "id":"811e1dcf-26fb-4d11-b956-0bd2fb0f2f6d",
            "name":"PEDIDO DE TESTE - Sanduíche",
            "unit":"UN",
            "index":1,
            "price":50,
            "options":[
               {
                  "id":"f13ae4b7-d0a1-4040-b525-860ffe9efa49",
                  "name":"Complemento 7",
                  "unit":"UN",
                  "index":2,
                  "price":0,
                  "addition":0,
                  "quantity":1,
                  "unitPrice":0
               }
            ],
            "imageUrl":"https://static-images.ifood.com.br/image/upload/t_high/pratos/d18dd059-d9b2-4758-b97c-f8c506d80949/202106211250_G6Q0_.jpeg",
            "quantity":1,
            "unitPrice":50,
            "totalPrice":50,
            "externalCode":"c01-i001",
            "optionsPrice":0
         }
      ],
      "total":{
         "benefits":0,
         "subTotal":50,
         "deliveryFee":8.9,
         "orderAmount":58.9,
         "additionalFees":0
      },
      "isTest":true,
      "customer":{
         "id":"22069c0b-8337-4ad7-993a-549eeb5c2acc",
         "name":"PEDIDO DE TESTE - Wagner Gomes Gonçalves",
         "phone":{
            "number":"0800 200 5011",
            "localizer":"11870462",
            "localizerExpiration":"2021-07-09T16:15:39.061993Z"
         },
         "documentNumber":"05166661699",
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
         "deliveryDateTime":"2021-07-09T13:55:39.061993Z"
      },
      "merchant":{
         "id":"d18dd059-d9b2-4758-b97c-f8c506d80949",
         "name":"Teste - FoodStock"
      },
      "payments":{
         "methods":[
            {
               "cash":{
                  "changeFor":0
               },
               "type":"OFFLINE",
               "value":58.9,
               "method":"CASH",
               "prepaid":false,
               "currency":"BRL"
            }
         ],
         "pending":58.9,
         "prepaid":0
      },
      "createdAt":"2021-07-09T13:15:39.061993Z",
      "displayId":"9411",
      "orderType":"DELIVERY",
      "benefits":[
         {
            "targetId":"string",
            "sponsorshipValues":[
               {
                  "description":"Sponsorship information for benefits",
                  "name":"MERCHANT",
                  "value":5
               }
            ],
            "description":"A benefit applied to the order",
            "value":10,
            "target":"DELIVERY_FEE"
         }
      ],
      "orderTiming":"IMMEDIATE",
      "salesChannel":"IFOOD",
      "preparationStartDateTime":"2021-07-09T13:15:39.061993Z"
   }');
   $babelized = $orderBabel->toString();
   $orderBabelized = new OrderBabelized($babelized);
   dd($orderBabel, $orderBabelized);
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

*/