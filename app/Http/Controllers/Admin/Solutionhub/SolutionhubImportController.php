<?php

namespace App\Http\Controllers\Admin\Solutionhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Solutionhub\SolutionhubProduct;
use App\Models\Solutionhub\SolutionhubProductVariation;
use App\Models\Solutionhub\SolutionhubProductGallery;
use App\Models\Solutionhub\SolutionhubVariationAttribute;
use App\Models\Solutionhub\SolutionhubVariationAttributeValue;
use App\Models\Category;
use App\Models\Solutionhub\SolutionhubProductCategory;
use App\Models\Solutionhub\SolutionhubTag;
use App\Models\Solutionhub\SolutionhubBrand;
use App\Models\Solutionhub\SolutionhubProductBrand;
use Illuminate\Support\Facades\Validator;
use App\Models\Solutionhub\SolutionhubBackendTag;
use App\Models\Solutionhub\SolutionhubProductBackendTag;
use App\Models\Solutionhub\SolutionhubProductTag;
use App\Models\Solutionhub\SolutionhubProductDescriptionImage;
use App\Models\Solutionhub\SolutionhubProductFeaturePageImage;
use App\Models\Solutionhub\SolutionhubStore;
use DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Solutionhub\Product\AddProduct;
use App\Http\Requests\Admin\Solutionhub\Product\UpdateProduct;
use Storage;
use Intervention\Image\Facades\Image;
use App\Imports\SolutionhubProductsImport;
use App\Exports\SolutionhubProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class SolutionhubImportController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public  function index(Request $request) {
        return view('admin.solutionhub.products.import');
    }

    public  function store(Request $request) {




        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'product_csv' => 'required|mimes:csv,txt'
        ], [

            'product_csv.required' => 'Upload the csv file is mandatory',
            'product_csv.mimes' => 'Please upload the csv file only',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $CsvFile = Excel::toArray(new SolutionhubProductsImport(), $request->file('product_csv'));
            $storagePath= env('STORAGE_PATH') ?? 'https://petparent.s3.ap-south-1.amazonaws.com/';
            if (!empty($CsvFile)) {

                $csvData = [];


                foreach($CsvFile as $key => $value) {
                    foreach($value as $key => $csv) {
                        if ($key != 0) {
                            array_push($csvData, $csv);
                        }
                    }
                    $header = $value[0];
                }
                foreach($csvData as $key => $value) {
                
                
                    $variation = [];
                      foreach($header as $key => $val) {
                  
                        (strtolower($val) == 'product') ? ($product = $value[$key]) : '';
                        (strtolower($val) == 'description') ? ($description = $value[$key]) : '';
                        (strtolower($val) == 'status') ? ($status = $value[$key]) : '';
                        (strtolower($val) == 'feature_image') ? ($feature_image = $value[$key]) : '';
                        (strtolower($val) == 'category') ? ($category = $value[$key]) : '';
                        (strtolower($val) == 'backend_tag') ? ($backend_tag = $value[$key]) : '';
                        (strtolower($val) == 'tag') ? ($tag = $value[$key]) : '';
                        (strtolower($val) == 'brand') ? ($brand = $value[$key]) : '';


                    }
                   
                   
                    $backend_tag = explode(',', $backend_tag);
                    $tag = explode(',', $tag);
                    $brand = explode(',', $brand);
                    $category = explode(',', $category);
              
        
                    if ($product ) {
                        $products = SolutionhubProduct::create([
                            'productName' => $product .'copy',
                            'description' => $description,
                            'status' => $status ?? 0,
                            'feature_image' => (!empty($feature_image)) ? $storagePath.$feature_image : null,

                         
                        ]);
                       
                        if (!empty($tag)) {
                            foreach($tag as $tagName) {

                                $tags = SolutionhubTag::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new SolutionhubProductTag;
                                $tagValue->tag_id = $tags->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }
                       
                        if (!empty($backend_tag)) {
                            foreach($backend_tag as $vakey => $tagName) {

                                $tag = SolutionhubBackendTag::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new SolutionhubProductBackendTag;
                                $tagValue->tag_id = $tag->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }
                        if (!empty($brand)) {
                            foreach($brand as $vakey => $tagName) {

                                $tag = SolutionhubBrand::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new SolutionhubProductBrand;
                                $tagValue->brand_id = $tag->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                         
                        }
                        if (!empty($category)) {
                            foreach($category as $vakey => $value) {
    
                             $category= Category::where('name',trim($value))->first();
                            if(!empty($category)){
                                $categorySave = new SolutionhubProductCategory;
                                $categorySave->category_id = $category->id;
                                $categorySave->product_id = $products->id;
                                $categorySave->save();
                            }
                            }
                        }
                    } else {
                        return redirect()->back()->with('error', 'Csv file in not matched!');
                    }
                 
                  
                }
            }
        }
        return redirect()->back()->with('success', 'Product Imported successfully!');
    }

    public function export (Request $request) {

        return Excel::download(new SolutionhubProductsExport($request->id), 'export.csv');
    }

}
