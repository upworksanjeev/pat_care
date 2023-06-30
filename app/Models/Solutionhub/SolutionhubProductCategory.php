<?php

namespace App\Models\Solutionhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolutionhubProductCategory extends Model
{

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'product_id', 'category_id'
    ];

    public function categoryName()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

}
