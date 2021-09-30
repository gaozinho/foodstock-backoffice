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
use App\Actions\ProductionLine\ConcludeProductionProccess;
use App\Models\FederatedSale;
use App\Actions\ProductionLine\GenerateOrderJson;
use Illuminate\Support\Facades\Log;


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

        Log::info("Order created: " . $order->id);

        try{
            $this->startOneOrder($order);
            return $this->sendResponse(new IfoodOrderResource($order), 'IfoodOrder saved successfully.');
        }catch(\Exception $e){
            return $this->sendResponse(["success" => false, "order_id" => $order->id], 'Cant save IfoodOrder. ' . $e->getMessage());
        }        
    }

    public function restartProduction(Request $request){
        $orders = [];
        $input = $request->all();
        if(isset($input["restaurant_id"])){
            $orders = Order::where("restaurant_id", $input["restaurant_id"])->get();
        }else if(isset($input["order_id"])){
            $orders = Order::where("id", $input["order_id"])->get();
        }else if(isset($input["created_at"])){
            $orders = Order::where("created_at", ">", $input["created_at"])->get();
        }

        foreach($orders as $order){
            $this->startOneOrder($order);
        }
        return $orders->pluck("order_id", "id");
    }

    public function reprocessSales(Request $request){
        $input = $request->all();
        
        $ordersReturn = [
            "total" => 0,
            "success" => [],
            "error" => [],
        ];
        if(isset($input["restaurant_id"])){
            
            $orders = Order::where("restaurant_id", $input["restaurant_id"]);
            if(isset($input["created_at"])) $orders->whereRaw("DATE(created_at) = '" . $input["created_at"] . "'");
            $orders = $orders->get();

            $ordersReturn["total"] = count($orders);

            foreach($orders as $order){
                
                $generateOrderJson = new GenerateOrderJson($order);
                $orderJson = $generateOrderJson->generate();

                try{
                    FederatedSale::create(array_merge([
                        "restaurant_id" => $order->restaurant_id, 
                        "broker_id" => $order->broker_id], (array) $orderJson));
                    $ordersReturn["success"][] = $order->id;
                }catch(\Exception $e){
                    $ordersReturn["error"][] = ["message" => $e->getMessage(), "order" => $order->id];
                }

            }
            
            return $ordersReturn;
        }
    }

    private function startOneOrder($order){
        $startProductionProccess = new StartProductionProccess();
        $productionMovement = $startProductionProccess->start($order->id);
    }

    public function cancelProduction(Request $request)
    {

        $ifoodOrder = null;
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'broker_id' => 'required',
            'order_id' => 'required',
            'event_json' => 'required',
            'reason' => 'required',
            'code' => 'required',
            'origin' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try{
            $cancelProductionProccess = new CancelProductionProccess();
            $order_id = $cancelProductionProccess->cancel($input["order_id"], $input["broker_id"], $input["event_json"], $input["reason"], $input["code"], $input["origin"]);
            return $this->sendResponse(["order_id" => $input["order_id"]], 'Order canceled successfully.');
        }catch(\Exception $e){
            if(env("APP_DEBUG")) throw $e;
            return $this->sendResponse(["success" => false, "order_id" => $input["order_id"]], 'Cant cancel order.');
        }
    }    

    public function concludeProduction(Request $request)
    {

        $ifoodOrder = null;
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'order_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try{
            $concludeProductionProccess = new ConcludeProductionProccess();
            $order_id = $concludeProductionProccess->conclude($input["order_id"]);
            return $this->sendResponse(["order_id" => $input["order_id"]], 'Order concluded successfully.');
        }catch(\Exception $e){
            //if(env("APP_DEBUG")) throw $e;
            return $this->sendResponse(["success" => false, "order_id" => $input["order_id"]], 'Cant conclude order.');
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