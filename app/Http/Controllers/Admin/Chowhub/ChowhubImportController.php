<?php

namespace App\Http\Controllers\Admin\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ChowhubProduct;
use App\Models\ChowhubProductVariation;
use App\Models\ChowhubProductGallery;
use App\Models\ChowhubVariationAttribute;
use App\Models\ChowhubVariationAttributeValue;
use App\Models\Category;
use App\Models\ChowhubTag;
use App\Models\ChowhubBrand;
use App\Models\ChowhubProductBrand;
use Illuminate\Support\Facades\Validator;
use App\Models\ChowhubBackendTag;
use App\Models\ChowhubProductBackendTag;
use App\Models\ChowhubProductTag;
use App\Models\ChowhubProductDescriptionImage;
use App\Models\ChowhubProductFeaturePageImage;
use App\Models\ChowhubStore;
use DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Chowhub\Product\AddProduct;
use App\Http\Requests\Admin\Chowhub\Product\UpdateProduct;
use Storage;
use Intervention\Image\Facades\Image;
use App\Imports\ChowhubProductsImport;
use App\Exports\ChowhubProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ChowhubImportController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public  function index(Request $request) {
        return view('admin.chowhub.products.import');
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
            $CsvFile = Excel::toArray(new ChowhubProductsImport(), $request->file('product_csv'));
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
                        (strtolower($val) == 'variation') ? (array_push($variation, $value[$key])) : '';
                        (strtolower($val) == 'attributes') ? ($attribute = $value[$key]) : '';
                        (strtolower($val) == 'description_images') ? ($description_images = $value[$key]) : '';
                        (strtolower($val) == 'experimental_page_images') ? ($feature_page_images = $value[$key]) : '';
                        (strtolower($val) == 'media_image') ? ($media_image = $value[$key]) : '';
                        (strtolower($val) == 'product') ? ($product = $value[$key]) : '';
                        (strtolower($val) == 'description') ? ($description = $value[$key]) : '';
                        (strtolower($val) == 'sku') ? ($sku = $value[$key]) : '';
                        (strtolower($val) == 'pet_type') ? ($pet_type = $value[$key]) : '';
                        (strtolower($val) == 'age') ? ($age = $value[$key]) : '';
                        (strtolower($val) == 'food_type') ? ($food_type = $value[$key]) : '';
                        (strtolower($val) == 'protein_type') ? ($protein_type = $value[$key]) : '';
                        (strtolower($val) == 'type') ? ($type = $value[$key]) : '';
                        (strtolower($val) == 'store') ? ($store_id = $value[$key]) : '';
                        (strtolower($val) == 'feature_image') ? ($feature_image = $value[$key]) : '';
                        (strtolower($val) == 'real_price') ? ($real_price = $value[$key]) : '';
                        (strtolower($val) == 'sale_price') ? ($sale_price = $value[$key]) : '';
                        (strtolower($val) == 'weight') ? ($weight = $value[$key]) : '';
                        (strtolower($val) == 'quantity') ? ($quantity = $value[$key]) : '';
                        (strtolower($val) == 'status') ? ($status = $value[$key]) : '';
                        (strtolower($val) == 'backend_tag') ? ($backend_tag = $value[$key]) : '';
                        (strtolower($val) == 'tag') ? ($tag = $value[$key]) : '';
                        (strtolower($val) == 'brand') ? ($brand = $value[$key]) : '';


                    }

                    $variation = array_filter($variation);
                    $attribute = rtrim($attribute, ';');
                    $media_image = rtrim($media_image, ',');
                    $description_images = rtrim($description_images, ',');
                    $feature_page_images = rtrim($feature_page_images, ',');
                    $media_image = explode(',', $media_image);
                    $description_images = explode(',', $description_images);
                    $feature_page_images = explode(',', $feature_page_images);
                    $backend_tag = explode(',', $backend_tag);
                    $tag = explode(',', $tag);
                    $brand = explode(',', $brand);

                    if(!empty($store_id)){
                        $store=ChowhubStore::where('name',$store_id)->first();
                        if(!empty($store)){
                            $store_id= $store->id ?? null;

                        }
                    }

                    if ($product ) {
                        $products = ChowhubProduct::create([
                            'productName' => $product.'copy',
                            'description' => $description,
                            'sku' => $sku,
                            'pet_type' => $pet_type,
                            'age' => json_encode(explode(',', $age)),
                            'food_type' => $food_type,
                            'protein_type' => json_encode(explode(',', $protein_type)),
                            'type' => $type,
                            'store_id' => $store_id,
                            'feature_image' => (!empty($feature_image)) ? $storagePath.$feature_image : null,
                            'real_price' => $real_price,
                            'sale_price' => $sale_price,
                            'weight' => json_encode(explode(',', $weight)),
                            'quantity' => $quantity,
                            'status' => $status,
                        ]);
                        if (!empty($description_images)) {
                         
                            foreach($description_images as $key => $value) {
                              if(!empty($value)){
                                ChowhubProductDescriptionImage::create([
                                    'product_id' => $products->id,
                                    'image_path' => $storagePath.$value,
                                    'priority' => $key,
                                ]);
                              }
                            }
                        }

                        if (!empty($feature_page_images)) {

                            foreach($feature_page_images as $key => $value) {
                              if(!empty($value)){
                                ChowhubProductFeaturePageImage::create([
                                    'product_id' => $products->id,
                                    'image_path' => $storagePath.$value,
                                    'priority' => $key,
                                ]);
                              }
                            }
                        }
                        if (!empty($media_image)) {

                            foreach($media_image as $key => $value) {
                         
                              if(!empty($value)){
                                ChowhubProductGallery::create([
                                  'product_id' => $products->id,
                                  'image_path' => $storagePath.$value,
                                  'priority' => $key,

                              ]);
                              }
                                

                            }
                        }
                        if (!empty($tag)) {
                            foreach($tag as $tagName) {

                                $tags = ChowhubTag::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new ChowhubProductTag;
                                $tagValue->tag_id = $tags->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }
                        if (!empty($backend_tag)) {
                            foreach($backend_tag as $vakey => $tagName) {

                                $tag = ChowhubBackendTag::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new ChowhubProductBackendTag;
                                $tagValue->tag_id = $tag->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }
                        if (!empty($brand)) {
                            foreach($brand as $vakey => $tagName) {

                                $tag = ChowhubBrand::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new ChowhubProductBrand;
                                $tagValue->brand_id = $tag->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }

                        if (!empty($attribute)) {
                            $attribute = rtrim($attribute, ';');
                            $attribute = explode(';', $attribute);

                            $attr = [];
                            foreach($attribute as $vakey => $attributeData) {

                                $attributeName = explode('=', $attributeData);

                                if (isset($attributeName[1])) {
                                    $attri = $attributeName[1];
                                }


                                $variationAttribute = ChowhubVariationAttribute::updateOrCreate([
                                    'name' => $attributeName[0] ?? null
                                ], [
                                    'name' => $attributeName[0] ?? null
                                ]);

                                if ($variationAttribute->id && $attri) {

                                    array_push($attr, $variationAttribute->id);

                                    $variationAttrArrs = explode(",", $attri);

                                    foreach($variationAttrArrs as $variationAttrArr) {
                                        $variationAttributeValue = new ChowhubVariationAttributeValue;
                                        $variationAttributeValue->attribute_id = $variationAttribute->id;
                                        $variationAttributeValue->product_id = $products->id;
                                        $variationAttributeValue->name = $variationAttrArr;
                                        $variationAttributeValue->save();
                                    }
                                }
                            }
                          
                            if (!empty($variation)) {

                            
                                foreach($variation as $variations) {

                                
                                    $variationdynamic = [];
                                    $variations = explode(',', $variations);
                                 
                                    foreach($variations as $key => $value) {
                                        $value = explode('=', $value);

                                        ($value[0] == 'qty') ? ($variationQty = $value[1]) : 0;
                                        ($value[0] == 'weight') ? ($variationWeight = $value[1]) : 0;
                                        ($value[0] == 'real_price') ? ($variationRealPrice = $value[1]) : 0;
                                        ($value[0] == 'sale_price') ? ($variationSalePrice = $value[1]) : 0;
                                        ($value[0] == 'sku') ? ($variationSku = $value[1]) : 0;
                                        ($value[0] == 'image') ? ($variationImage = $value[1]) : null;
                                        array_push($variationdynamic, $value);


                                    }
                                  

                                    $productVariation = new ChowhubProductVariation;
                                    $productVariation->product_id = $products->id;

                                    $variationAttributeIds = [];
                                    foreach($variationdynamic as $key => $attribute) {
                                        if ($attribute) {

                                            if (isset($attribute[1])) {
                                                $attrVal = $attribute[1];
                                            }
                                            $attr = ChowhubVariationAttribute::where('name', $attribute[0])->first();

                                            if ($attr) {
                                                $selectedAttrubutes = ChowhubVariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $attrVal, 'attribute_id' => $attr->id])->first();

                                                if ($selectedAttrubutes) {
                                                    $AttributesArray = [];
                                                    $AttributesArray['attribute_id'] = $selectedAttrubutes->id;
                                                    $AttributesArray['attribute_name_id'] = $selectedAttrubutes->attribute_id;
                                                    array_push($variationAttributeIds, $AttributesArray);
                                                }
                                            }

                                        }
                                    }
                                 
                                    $productVariation->real_price = $variationRealPrice ?? 0;
                                    $productVariation->sale_price = $variationSalePrice ?? 0;

                                    $productVariation->quantity = $variationQty ?? 0;
                                    $productVariation->weight = $variationWeight ?? 0;
                                    $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                                    $productVariation->sku = $variationSku ?? null;

                                    if(!empty($variationImage)){
                                        $productVariation->image = $storagePath.$variationImage ?? null;
                                    }
                                    $productVariation->save();

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

        return Excel::download(new ChowhubProductsExport($request->id), 'export.csv');
    }

}
