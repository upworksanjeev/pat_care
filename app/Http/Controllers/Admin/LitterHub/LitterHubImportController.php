<?php

namespace App\Http\Controllers\Admin\Litterhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Litterhub\LitterhubProduct;
use App\Models\Litterhub\LitterhubProductVariation;
use App\Models\Litterhub\LitterhubProductGallery;
use App\Models\Litterhub\LitterhubVariationAttribute;
use App\Models\Litterhub\LitterhubVariationAttributeValue;
use App\Models\Litterhub\Category;
use App\Models\Litterhub\LitterhubTag;
use App\Models\Litterhub\LitterhubBrand;
use App\Models\Litterhub\LitterhubProductBrand;
use Illuminate\Support\Facades\Validator;
use App\Models\Litterhub\LitterhubBackendTag;
use App\Models\Litterhub\LitterhubProductBackendTag;
use App\Models\Litterhub\LitterhubProductTag;
use App\Models\Litterhub\LitterhubProductDescriptionImage;
use App\Models\Litterhub\LitterhubProductFeaturePageImage;
use App\Models\Litterhub\LitterhubStore;
use Storage;
use Intervention\Image\Facades\Image;
use App\Imports\LitterhubProductsImport;
use App\Exports\LitterhubProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class LitterhubImportController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public  function index(Request $request) {
        return view('admin.litterhub.products.import');
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
            $CsvFile = Excel::toArray(new LitterhubProductsImport(), $request->file('product_csv'));
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
                        (strtolower($val) == 'scented') ? ($scented = $value[$key]) : '';
                        (strtolower($val) == 'cat_count') ? ($cat_count = $value[$key]) : '';
                        (strtolower($val) == 'litter_material') ? ($litter_material = $value[$key]) : '';
                        (strtolower($val) == 'clumping') ? ($clumping = $value[$key]) : '';
                        (strtolower($val) == 'type') ? ($type = $value[$key]) : '';
                        (strtolower($val) == 'store') ? ($store_id = $value[$key]) : '';
                        (strtolower($val) == 'feature_image') ? ($feature_image = $value[$key]) : '';
                        (strtolower($val) == 'real_price') ? ($real_price = $value[$key]) : '';
                        (strtolower($val) == 'sale_price') ? ($sale_price = $value[$key]) : '';
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
                        $store=LitterhubStore::where('name',$store_id)->first();
                        if(!empty($store)){
                            $store_id= $store->id ?? null;

                        }
                    }

                    if ($product ) {
                        $products = LitterhubProduct::create([
                            'productName' => $product.'copy',
                            'description' => $description,
                            'sku' => $sku,
                            'scented' => $scented,
                            'litter_material' => json_encode(explode(',', $litter_material)),
                            'cat_count' => $cat_count,
                            'clumping' => $clumping,
                            'type' => $type,
                            'store_id' => $store_id,
                            'feature_image' => (!empty($feature_image)) ? $storagePath.$feature_image : null,
                            'real_price' => $real_price,
                            'sale_price' => $sale_price,
                            'quantity' => $quantity,
                            'status' => $status,
                        ]);
                        if (!empty($description_images)) {
                         
                            foreach($description_images as $key => $value) {
                              if(!empty($value)){
                                LitterhubProductDescriptionImage::create([
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
                                LitterhubProductFeaturePageImage::create([
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
                                LitterhubProductGallery::create([
                                  'product_id' => $products->id,
                                  'image_path' => $storagePath. $value,
                                  'priority' => $key,

                              ]);
                              }
                                

                            }
                        }
                        if (!empty($tag)) {
                            foreach($tag as $tagName) {

                                $tags = LitterhubTag::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new LitterhubProductTag;
                                $tagValue->tag_id = $tags->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }
                        if (!empty($backend_tag)) {
                            foreach($backend_tag as $vakey => $tagName) {

                                $tag = LitterhubBackendTag::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new LitterhubProductBackendTag;
                                $tagValue->tag_id = $tag->id;
                                $tagValue->product_id = $products->id;
                                $tagValue->save();
                            }
                        }
                        if (!empty($brand)) {
                            foreach($brand as $vakey => $tagName) {

                                $tag = LitterhubBrand::updateOrCreate([
                                    'name' => trim(strtolower($tagName))
                                ], [
                                    'name' => trim(strtolower($tagName))
                                ]);

                                $tagValue = new LitterhubProductBrand;
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


                                $variationAttribute = LitterhubVariationAttribute::updateOrCreate([
                                    'name' => $attributeName[0] ?? null
                                ], [
                                    'name' => $attributeName[0] ?? null
                                ]);

                                if ($variationAttribute->id && $attri) {

                                    array_push($attr, $variationAttribute->id);

                                    $variationAttrArrs = explode(",", $attri);

                                    foreach($variationAttrArrs as $variationAttrArr) {
                                        $variationAttributeValue = new LitterhubVariationAttributeValue;
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
                                  

                                    $productVariation = new LitterhubProductVariation;
                                    $productVariation->product_id = $products->id;

                                    $variationAttributeIds = [];
                                    foreach($variationdynamic as $key => $attribute) {
                                        if ($attribute) {

                                            if (isset($attribute[1])) {
                                                $attrVal = $attribute[1];
                                            }
                                            $attr = LitterhubVariationAttribute::where('name', $attribute[0])->first();

                                            if ($attr) {
                                                $selectedAttrubutes = LitterhubVariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $attrVal, 'attribute_id' => $attr->id])->first();

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
                            // DB::commit();
                        }
                    } else {
                        return redirect()->back()->with('error', 'Csv file in not matched!');
                    }
                 
                //   } catch (\Throwable $th) {
                //     DB::rollback();
                //     echo $th;die;
                //     echo "herer errro";
                // }
                }
            }
        }
        return redirect()->back()->with('success', 'Product Imported successfully!');
    }

    public function export (Request $request) {

        return Excel::download(new LitterhubProductsExport($request->id), 'export.csv');
    }

}
