<?php

namespace App\Exports;

use App\Models\ChowhubProduct;
use App\Models\ChowhubProductVariation;
use App\Models\ChowhubProductGallery;
use App\Models\ChowhubVariationAttribute;
use App\Models\ChowhubVariationAttributeValue;
use App\Models\Category;
use App\Models\ChowhubTag;
use App\Models\ChowhubBrand;
use App\Models\ChowhubProductBrand;


use App\Models\ChowhubBackendTag;
use App\Models\ChowhubProductBackendTag;
use App\Models\ChowhubProductTag;
use App\Models\ChowhubProductDescriptionImage;
use App\Models\ChowhubProductFeaturePageImage;
use App\Models\ChowhubStore;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\withHeadings;
class ChowhubProductsExport implements FromCollection
// ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
           $this->id = $id;
    }
    // public function headings(): array {
    //     return [
    //         "product","description","sku","pet_type","age","food_type","protein_type","type","store_id","feature_image","real_price","sale_price","weight","quantity","status","backend_tag","tag","brand","media_image","description_images","experimental_page_images","attributes","variation","variation","variation","variation"
    //     ];
    // }
    public function collection()
    {
        $id=$this->id;
        $product_id=explode(',',$id);
        $finalData=[];
        $header['productName']="product" ?? null;
        $header['description']='description' ?? null;
        $header['sku']='sku' ?? null;
        $header['pet_type']='pet_type' ?? null;
        $header['age']='age' ?? null;
        $header['food_type']='food_type' ?? null;
        $header['protein_type']='protein_type' ?? null;
        $header['type']='type' ?? null;
        $header['store_id']='store' ?? null;
        $header['feature_image']='feature_image' ?? null;
        $header['real_price']='real_price' ?? null;
        $header['sale_price']='sale_price' ?? null;
        $header['weight']='weight' ?? null;
        $header['quantity']='quantity' ?? null;
        $header['status']='status' ?? null;
        $header['backend_tag']='backend_tag' ?? null;
        $header['tag']='tag'?? null;
        $header['brand']='brand' ?? null;
        $header['media']='media_image' ?? null;
        $header['description_images']='description_images' ?? null;
        $header['feature_page_images']='experimental_page_images'?? null;
        $header['attributes']='attributes'?? null;
      

        
        foreach ($product_id as $key => $id) {
    
            $product = ChowhubProduct::find($id);
            $productGallery = ChowhubProductGallery::where('product_id',$id)->get();
            $variationAttributeValue = ChowhubVariationAttributeValue::where('product_id',$id)->get();
            $chowhubProductDescriptionImage = ChowhubProductDescriptionImage::where('product_id',$id)->get();
            $chowhubProductFeaturePageImage = ChowhubProductFeaturePageImage::where('product_id',$id)->get();
            $productVariation = ChowhubProductVariation::where('product_id',$id)->get();
        
            if($product){
               
                $media='';
                $description_images='';
                $feature_page_images='';
                $data['productName']=$product->productName ?? null;
                $data['description']=$product->description ?? null;
                $data['sku']=$product->sku ?? null;
                $data['pet_type']=$product->pet_type ?? null;
                $data['age']=str_replace(array('"','[',']','\\'),'',$product->age)  ?? null;
                $data['food_type']=$product->food_type ?? null;
                $data['protein_type']=str_replace(array('"','[',']','\\'),'',$product->protein_type) ?? null;
                $data['type']=$product->type ?? null;
                if(!empty($product->store_id)){
                    $store= ChowhubStore::find($product->store_id);
                    if(!empty($store)){
                        $store_name= $store->name;
                    }
                   
                }

                $data['store_id']=$store_name ?? null;
                if($product->feature_image){
                    $product->feature_image = explode('images',$product->feature_image);
                    $path='images'.$product->feature_image[1];
                }else{
                    $path= '';
                }
                $data['feature_image']=$path ?? null;
                $data['real_price']=$product->real_price ?? null;
                $data['sale_price']=$product->sale_price ?? null;
                $data['weight']=str_replace(array('"','[',']','\\'),'',$product->weight) ?? null;
                $data['quantity']=$product->quantity ?? null;
                $data['status']=$product->status ?? null;
                $data['backend_tag']=$product->availBackendTags ?? null;
                $data['tag']=$product->availTags ?? null;
                $data['brand']=$product->availBrands ?? null;
               
              
                if($productGallery){
                    foreach ($productGallery as $key => $value) {
                        $value->image_path= explode('images',$value->image_path);
                      
                        $media .= 'images'. $value->image_path[1].','; 
                        }
                }
                if($chowhubProductDescriptionImage){
                    foreach ($chowhubProductDescriptionImage as $key => $value) {
                        $value->image_path= explode('images',$value->image_path);
                        $description_images .= 'images'. $value->image_path[1].','; 
                        }
                } if($chowhubProductFeaturePageImage){
                    foreach ($chowhubProductFeaturePageImage as $key => $value) {
                        $value->image_path= explode('images',$value->image_path);
                        $feature_page_images .= 'images'. $value->image_path[1].','; 
                        }
                }
                $data['media']=$media ?? null;
                $data['description_images']=$description_images ?? null;
                $data['feature_page_images']=$feature_page_images ?? null;
                if($productVariation){
                    $var=[];
                    foreach ($productVariation as $key => $value) {
                    
                        $image='';
                        $real_price=$value->real_price ?? null;
                        $sale_price=$value->sale_price ?? null;
                        $image=$value->image ?? null;
                        if($value->image){
                            $value->image= explode('images',$value->image);
                            $image = 'images'. $value->image[1].',' ?? null; 
                        }
                        $weight=$value->weight ?? null;
                        $quantity=$value->quantity ?? null;
                        $sku=$value->sku ?? null;
                        $attr='' ?? null ;
                        $variationData='qty='.$quantity.',weight='.$weight.',real_price='.$real_price.',sale_price='.$sale_price.',sku='.$sku.',image='.$image.'';
                    
                        $allvariations = json_decode($value->variation_attributes_name_id);

                            $viewData = [];
                         
                            foreach ($allvariations as $variat)
                            {

                                $attr_name = ChowhubVariationAttribute::where('id', $variat->attribute_name_id)->pluck('name')->first();
                                $attrValue = ChowhubVariationAttributeValue::where('id', $variat->attribute_id)->pluck('name')->first();
                                $viewData[$attr_name] = $attrValue ?? null;
                                
                            }
                            
                            foreach($viewData as $key => $view){
                                $attr .=''.$key.'='.$view.',';
                            }
                           
                            $attr.=$variationData;
                            
                          array_push($var,$attr);
                       
                        }
                         }
            

          
                    
              
                    $attributes = [];
                    foreach ($product->variationAttributesValue as $datas)
                    {
                        $attributes[$datas->variationAttributeName->name][] = $datas->name;
                    }

                    $at='' ?? null;
                    foreach ($attributes as $key => $attVal) {
                        $attVal=implode(',',$attVal);
                    $at.=$key.'='.$attVal.';';
                    
                    }
                    $data['attributes']= $at ?? null;
                    foreach ($var as $key => $value) {
                   
                        $data['variation_'. ++$key] = $value ?? null;
                        $header['variation_'.++$key]='variation'?? null;
                    }
                        
               
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
