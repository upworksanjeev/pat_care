<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubCartItem extends Model
{
    protected $fillable = ['product_id', 'cart_id', 'quantity','variation_product_id'];

    public function cart()
    {
        return $this->belongsTo(ChowhubCart::class);
    }

    public function product()
    {
        return $this->hasOne(ChowhubProduct::class,'id', 'product_id');
    }

    public function variationProduct()
    {
        return $this->hasOne(ChowhubProductVariation::class,'id', 'variation_product_id');
    }
}
