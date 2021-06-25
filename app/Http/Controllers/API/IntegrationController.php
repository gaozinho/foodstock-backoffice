<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\IfoodBroker;
use App\Models\Order;
use Validator;
use App\Http\Resources\IfoodOrder as IfoodOrderResource;

use App\Actions\ProductionLine\StartProductionProccess;

class IntegrationController extends BaseController
{

    public function startProduction(Request $request)
    {

        $ifoodOrder = null;
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'broker_id' => 'required',
            'restaurant_id' => 'required',
            'order_id' => 'required',
            'json' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $order = Order::firstOrCreate(
            ['order_id' => $input["order_id"]],
            $input
        );

        $startProductionProccess = new StartProductionProccess();
        $productionMovement = $startProductionProccess->start($order->id);

        return $this->sendResponse(new IfoodOrderResource($order), 'IfoodOrder saved successfully.');
    }

    public function checkIfCreated(Request $request)
    {
        try{
            $order = Order::where('order_id', $request->order_id)->firstOrFail();  
        }catch(\Exception $e){
            return $this->sendError('Not found.', []);  
        }
        
        return $this->sendResponse(["order_id" => $request->order_id], 'IfoodOrder was created successfully.');
    }    
}