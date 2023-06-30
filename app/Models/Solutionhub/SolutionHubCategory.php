<?php

namespace App\Models\Solutionhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolutionHubCategory extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'name'
    ];
    public function problem()
    {

        return $this->hasMany(SolutionHubProblem::class, 'solution_category_id', 'id');
    }
    public function solution()
    {

        return $this->hasMany(SolutionHubProblemSolution::class, 'solution_category_id', 'id');
    }
}
