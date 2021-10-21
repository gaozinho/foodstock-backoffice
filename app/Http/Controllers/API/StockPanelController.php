<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Validator;
use App\Models\StockPanel;
use Hashids\Hashids;

class StockPanelController extends BaseController
{
    public function create(Request $request)
    {
        $input = $request->all();

        

        $validator = Validator::make($input, [
            'name' => 'required|min:4|max:255',
            'ui' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $stockPanel = null;

        try{
            $decoded_user_id = (new Hashids('', 10))->decode($input["ui"])[0];
            if(intval($decoded_user_id) == 0) throw new \Exception();
            $stockPanel = StockPanel::where(function($query) use ($decoded_user_id){
                    $query->where("user_id", $decoded_user_id)
                        ->orWhere("user_id", null);
                })->where("name", $input["name"])->first();
            if(!is_object($stockPanel)) $stockPanel = StockPanel::create(["name" => $input["name"], "user_id" => $decoded_user_id]);
        }catch(\Exception $e){
            dd($e);
            return $this->sendError('Unable to create entry.', '');  
        }
   
        return $this->sendResponse($stockPanel, 'Created successfully');
    }
}
