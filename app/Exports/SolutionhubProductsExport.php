<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Solutionhub\SolutionhubProduct;
use App\Models\Solutionhub\SolutionhubTag;
use App\Models\Solutionhub\SolutionhubProductTag;
use App\Models\Solutionhub\SolutionhubBackendTag;
use App\Models\Solutionhub\SolutionhubProductBackendTag;
use App\Models\Solutionhub\SolutionhubBrand;
use App\Models\Solutionhub\SolutionhubProductBrand;
use App\Models\Category;
use DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Solutionhub\Product\AddProduct;
use App\Http\Requests\Admin\Solutionhub\Product\UpdateProduct;
use Storage;
use Intervention\Image\Facades\Image;
class SolutionhubProductsExport implements FromCollection
{
    protected $id;

    function __construct($id) {
           $this->id = $id;
    }



    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $id=$this->id;
        $product_id=explode(',',$id);
    
        $finalData=[];
        $header['productName']="product" ?? null;
        $header['description']='description' ?? null;
        $header['status']='status' ?? null;
        $header['feature_image']='feature_image' ?? null;
        $header['category']='category' ?? null;
        $header['backend_tag']='backend_tag' ?? null;
        $header['tag']='tag'?? null;
        $header['brand']='brand' ?? null;

        
        foreach ($product_id as $key => $id) {
    
            $product = SolutionhubProduct::with('category')->where('id', $id)->first();       
            if($product){
               
               
                $data['productName']=$product->productName ?? null;
                $data['description']=$product->description ?? null;
                $data['status']=$product->status ?? null;
                if($product->feature_image){
                    $product->feature_image = explode('images',$product->feature_image);
                    $path='images'.$product->feature_image[1];
                }else{
                    $path= '';
                }
                $procategory='';
                if($product->category){
                    foreach ($product->category as $key => $value) {
                       
                    $categoryName = Category::find($value->category_id);
                    if(!empty($categoryName)){
                       $procategory .=$categoryName->name.',';
                    }
                    
                   
                    }
                }
                $data['feature_image']=$path ?? null;
                $data['category']=rtrim($procategory, ',') ?? null;
                $data['backend_tag']=$product->availBackendTags ?? null;
                $data['tag']=$product->availTags ?? null;
                $data['brand']=$product->availBrands ?? null;
              
                
            

               
                 array_push($finalData,$data); 
                    while(count($data) > 0) {
                        array_pop($data);
                    }
            }
            
        }
        array_unshift($finalData,$header);
       
        return collect($finalData);
   
  
    }
}
