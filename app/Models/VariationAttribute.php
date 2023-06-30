<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationAttribute extends Model
{

    protected $table = 'variations_attributes';

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'name'
    ];

    public function variationAttributeName()
    {
        return $this->hasOne(VariationAttributeValue::class, 'attribute_id', 'id');
    }

}
