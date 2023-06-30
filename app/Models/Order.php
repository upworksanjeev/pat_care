<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'product_id',
        'variation_id',
        'transaction_id',
        'shipping_id',
        'user_id',
        'status',
        'grand_total',
        'item_count',
        'is_paid',
        'payment_method',
        'shippingmethod',
        'discount',
        'sub_total',
        'lightspeedOrderId',


        'remark'
    ];
    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

}
