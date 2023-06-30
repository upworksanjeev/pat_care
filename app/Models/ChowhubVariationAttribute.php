<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubVariationAttribute extends Model
{

    protected $table = 'chowhub_variations_attributes';

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
        return $this->hasOne(ChowhubVariationAttributeValue::class, 'attribute_id', 'id');
    }

}
