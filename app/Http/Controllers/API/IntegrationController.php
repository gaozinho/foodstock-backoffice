<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\IfoodBroker;
use App\Models\Order;
use Validator;
use App\Http\Resources\IfoodOrder as IfoodOrderResource;

use App\Actions\ProductionLine\StartProductionProccess;
use App\Actions\ProductionLine\CancelProductionProccess;


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

        try{
            $startProductionProccess = new StartProductionProccess();
            $productionMovement = $startProductionProccess->start($order->id);
            return $this->sendResponse(new IfoodOrderResource($order), 'IfoodOrder saved successfully.');
        }catch(\Exception $e){
            return $this->sendResponse(["success" => false, "order_id" => $order_id], 'Cant save IfoodOrder.');
        }

        
    }

    public function cancelProduction(Request $request)
    {

        $ifoodOrder = null;
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'broker_id' => 'required',
            'order_id' => 'required',
            'event_json' => 'required',
            'order_json' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try{
            $cancelProductionProccess = new CancelProductionProccess();
            $order_id = $cancelProductionProccess->cancel($input["order_id"], $input["broker_id"], $input["order_json"], $input["event_json"]);
            return $this->sendResponse(["order_id" => $order_id], 'IfoodOrder canceled successfully.');
        }catch(\Exception $e){
            return $this->sendResponse(["success" => false, "order_id" => $order_id], 'Cant cancel IfoodOrder.');
        }
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