<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubVariationAttributeValue extends Model
{

    protected $table = 'chowhub_variations_attributes_values';

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'attribute_id', 'product_id', 'name'
    ];

    public function variationAttributeName()
    {
        return $this->belongsTo(ChowhubVariationAttribute::class, 'attribute_id');
    }

}
