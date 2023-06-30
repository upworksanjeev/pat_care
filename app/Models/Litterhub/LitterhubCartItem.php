<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubCartItem extends Model
{
    protected $fillable = ['product_id', 'cart_id', 'quantity','variation_product_id'];

    public function cart()
    {
        return $this->belongsTo(LitterhubCart::class);
    }

    public function product()
    {
        return $this->hasOne(LitterhubProduct::class,'id', 'product_id');
    }

    public function variationProduct()
    {
        return $this->hasOne(LitterhubProductVariation::class,'id', 'variation_product_id');
    }
}
