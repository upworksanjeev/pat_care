<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubProductSku extends Model
{

    protected $table = 'products_sku';

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'product_id', 'product_variation', 'sku', 'qty'
    ];

    public function products()
    {
        return $this->belongsTo(LitterhubProduct::class, 'product_id');
    }

    public function productVariation()
    {
        return $this->belongsTo(LitterhubProductVariation::class, 'product_variation');
    }

}
