<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IfoodOrder extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'broker_id' => $this->broker_id,
            'restaurant_id' => $this->restaurant_id,
            'order_id' => $this->order_id,
            'json' => $this->json,      
        ];
    }
}
