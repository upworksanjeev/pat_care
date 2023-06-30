<?php

namespace App\Models\Solutionhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolutionHubProductProblem extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
    protected $fillable = [
        'product_id', 'category_id','problem_id'
    ];
    public function categoryName()
    {
        return $this->belongsTo(\App\Models\Solutionhub\SolutionHubProblem::class, 'problem_id');
    }
}
