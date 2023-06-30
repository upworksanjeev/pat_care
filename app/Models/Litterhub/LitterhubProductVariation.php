<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubProductVariation extends Model
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
        return $this->belongsTo(LitterhubProduct::class, 'product_id');
    }

    public function productSku()
    {
        return $this->belongsTo(LitterhubProductSku::class, 'sku_id');
    }

}
