<?php

namespace App\Models\Solutionhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolutionHubProductSolution extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'solution_id', 'category_id','product_id'
    ];
    public function categoryName()
    {
        return $this->belongsTo(\App\Models\Solutionhub\SolutionHubProblemSolution::class, 'solution_id');
    }
}
