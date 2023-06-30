<?php

namespace App\Imports;

use App\Models\LitterhubProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
class LitterhubProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
    }
    public function model(array $row)
    {
     
        return new LitterhubProduct([
         //


        ]);
    }
}
