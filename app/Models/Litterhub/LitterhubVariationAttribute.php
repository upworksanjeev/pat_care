<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubVariationAttribute extends Model
{

    protected $table = 'litterhub_variations_attributes';

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
        return $this->hasOne(LitterhubVariationAttributeValue::class, 'attribute_id', 'id');
    }

}
