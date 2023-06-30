<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubProductVariation extends Model
{

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'product_id', 'real_price', 'sale_price', 'image', 'variation_name', 'sku', 'variation_ids','variation_attributes_name_id','weight','quantity'
    ];

    public function products()
    {
        return $this->belongsTo(ChowhubProduct::class, 'product_id');
    }

    public function productSku()
    {
        return $this->belongsTo(ChowhubProductSku::class, 'sku_id');
    }

}
