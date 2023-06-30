<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Shipping\ShipingResource;
use App\Http\Resources\Orders\OrderItemsResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
           
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'grand_total' => $this->grand_total,
            'discount' => $this->discount,
            'sub_total' => $this->sub_total,
            'item_count' => $this->item_count,
            'is_paid' => $this->is_paid,
            'payment_method' => $this->payment_method,
            'shippingmethod' => $this->shippingmethod,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->user),
            'shipping' => new ShipingResource($this->shipping),
            'orderItems'=>OrderItemsResource::collection($this->orderItems),

            
           
                               
        ];
    }
}
