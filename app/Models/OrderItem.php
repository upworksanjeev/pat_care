<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'unit_price',
        'total_price',
        'quantity'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variationProduct()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

}
