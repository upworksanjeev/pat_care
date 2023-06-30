<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solutionhub\SolutionHubProblemSolution;
use App\Models\Solutionhub\SolutionHubProblem;
use App\Models\Solutionhub\SolutionHubCategory;


class SolutionhubCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $category=['Dog','Cat'];
       $problemDog=['Separation Anxiety','Teething','Aggressive Chewer','Boredom','Disabled','Energetic'];
       $solutionDog=['Chew Toys','Plush Toys','Fetch Toys','Rope & Tug Toys','Interactive Toys'];
       $problemCat=['Separation Anxiety','Overweight','Indoor','Boredom','Disabled','Energetic'];
       $solutionCat=['Interactive & Electronic Toys','Teasers & Wands','Scratchers','Balls & Chasers','Plush & Mice','Catnip Toys','Tunnels','Chew Toys'];



       foreach ($category as $key => $value) {
     
            $solutionHubCategory=SolutionHubCategory::create([
              'name' => $value
            ]);
            if($value == 'Dog'){
              foreach ($problemDog as $key => $problem) {
                SolutionHubProblem::create([
                'name' => $problem,
                'solution_category_id' => $solutionHubCategory->id,
                ]);
              }
              foreach ($solutionDog as $key => $solution) {
                SolutionHubProblemSolution::create([
                'name' => $solution,
                'solution_category_id' => $solutionHubCategory->id,
                ]);
              }
            }else{
              foreach ($problemCat as $key => $problem) {
                SolutionHubProblem::create([
                'name' => $problem,
                'solution_category_id' => $solutionHubCategory->id,
                ]);
              }
              foreach ($solutionCat as $key => $solution) {
                SolutionHubProblemSolution::create([
                'name' => $solution,
                'solution_category_id' => $solutionHubCategory->id,
                ]);
              }
            }

       }
    }

}
