<?php

namespace App\Traits;


use App\Models\LightSpeed;
use App\Models\LightSpeedAttributes;
use App\Models\Category;
use App\Models\Store;
use App\Models\Brand;



trait ProductTrait {

  public function checkAttributes($inputs){
    $attrName= [];
 
  foreach ($inputs as $key => $value) {
    array_push($attrName,$key);
  }
  $attrNames= implode("/",$attrName);
 
  $attributeId= LightSpeedAttributes::where('name', $attrNames)->first();
  if(empty($attributeId)){
    $count= count($attrName);
    $attributeName1= $attrName[0] ?? '';
    $attributeName2= $attrName[1] ?? '';
    $attributeName3= $attrName[2] ?? '';
   
        if($count == 1){
          $params= [
            "name"=> $attrNames,
            "attributeName1"=> $attributeName1,
          ];
        }elseif($count == 2){
          $params= [
            "name"=> $attrNames,
            "attributeName1"=> $attributeName1,
            "attributeName2"=> $attributeName2,
          ];
        }else{
          $params= [
            "name"=> $attrNames,
            "attributeName1"=> $attributeName1,
            "attributeName2"=> $attributeName2,
            "attributeName3"=> $attributeName3,
          ];
        }
  
      $params= json_encode($params);
      $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
      $accountID= $lightspeed->account_id;
      $url=env('LIGHT_SPEED_URL').$accountID."/ItemAttributeSet.json";
      $curl = curl_init();
  
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$lightspeed->access_token,
            'Content-Type: application/json'
          ),
        ));
  
        $response = curl_exec($curl);
  
        curl_close($curl);
        $result=json_decode($response);
      
          if(isset($result->ItemAttributeSet->itemAttributeSetID)){
            LightSpeedAttributes::create([
              'name'=> $result->ItemAttributeSet->name,
              'attributes_id'=> $result->ItemAttributeSet->itemAttributeSetID,

            ]);
            return $result->ItemAttributeSet->itemAttributeSetID;
            }
            
            }else{
              return $attributeId->attributes_id;
            }

  }


  public function storeProductMatrix($attrbuteId,$inputs,$productid){
  
   $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
   $accountID= $lightspeed->account_id;
   $url=env('LIGHT_SPEED_URL').$accountID."/ItemMatrix.json";

   $method='POST';

    $matrixId= $this->storeMatrix($inputs,$url,$method, $lightspeed->access_token,$attrbuteId,$productid);
    if($inputs['feature_image'] &&  $matrixId){
      $matrixImage= $this->storeMatrixImage($inputs['feature_image'],$url,$lightspeed->access_token, $matrixId);
    }
    if($matrixId){
      return $matrixId;
    }


  }


  public function storeMatrix($inputs,$url,$method, $token,$attrbuteId,$productid){

    $description= '#'.$productid.'-'.$inputs['productName'] ?? '';
    $defaultCost= $inputs['real_price'] ?? 0;
    $itemAttributeSetID= $attrbuteId ?? '';
    $sale_price= $inputs['sale_price'] ?? '';

    $category = Category::where('id',$inputs['category_id'] ?? '')->pluck('lightspeed_category_id')->first();
    $vendor = Store::where('id', $inputs['store_id'] ?? '')->pluck('lightspeed_vendor_id')->first();
    $brand = Brand::where('id', $inputs['brand_id'] ?? '')->pluck('lightspeed_brand_id')->first();

    $params=[
      "description"=> $description,
      "tax"=> true,
      "defaultCost"=> $defaultCost,
      "itemType"=> "box",
      "serialized"=> true,
      "itemAttributeSetID"=> $itemAttributeSetID,
      "manufacturerID"=>  $brand ?? 0,
      "categoryID"=> $category ?? 0,
      "defaultVendorID"=> $vendor ?? 0,
      "taxClassID"=> "0",
      "Items"=> "1",
      "seasonID"=> "0",
      "departmentID"=> "0",
      "Prices"=> [
        "ItemPrice"=>[
          "0"=>[
            "amount"=>$defaultCost,
            "useTypeID"=>"1",
            "useType"=>"Default",

          ],
          "1" => [
            "amount"=>$sale_price,
            "useTypeID"=>"2",
            "useType"=>"MSRP",
          ]
        ]
      ]

    ];
    $params= json_encode($params);
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POSTFIELDS =>$params,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $result =  json_decode($response);
  
    if(isset($result->ItemMatrix->itemMatrixID)){
      return $result->ItemMatrix->itemMatrixID;
    }
    
  }


  public function storeMatrixImage($inputs, $url,$token, $matrixId){
  
      $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
      $accountID= $lightspeed->account_id;
      $uri=env('LIGHT_SPEED_URL').$accountID."/ItemMatrix";
      $url = $uri."/".$matrixId."/Image.json";
        
      $headers = array(
          'Authorization: Bearer '.$token,
          'Accept: application/json',
          'Content-Type: multipart/form-data');
       
      $imagefile = $inputs; //Full path of image file you wish to use

      $postfields = array(
          'data' => '{
            "description": "Test Image",
            "ordering": 1
        }',
          'image' => new \CURLFile($imagefile, 'text/plain', 'testimage.jpg'));

      $filesize = filesize($imagefile);
      
      $ch = curl_init();

      $options = array(
          CURLOPT_URL => $url,
          CURLOPT_HEADER => true,
          CURLOPT_POST => 1,
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_VERBOSE => 1,
          CURLOPT_POSTFIELDS => $postfields,
          CURLOPT_INFILESIZE => $filesize,
          CURLOPT_RETURNTRANSFER => true
      ); 

      curl_setopt_array($ch, $options);
      $output = curl_exec($ch);

      curl_close($ch);

  }
  public function storeProductVariation($inputs,$products,$matrixId,$attrbuteId){
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Item.json";
    $method= 'POST';
    
    $productId= $this->storeProductVariationData($inputs,$url,$method, $lightspeed->access_token,$attrbuteId,$matrixId,$products);
  
    if($inputs['image'] &&  $productId){
      $this->storeProductVariationImage($inputs['image'],$url,$lightspeed->access_token, $productId);
    }
    if($productId){
      return $productId;
    }
  

  }
  public function storeProductVariationData($inputs,$url,$method, $token,$attrbuteId,$matrixId,$products){

 
    $attributes= LightSpeedAttributes::where('attributes_id',$attrbuteId)->pluck('name')->first();
    $attributes= explode("/",$attributes);
    $category = Category::where('id',$products['category_id'] ?? '')->pluck('lightspeed_category_id')->first();
    $vendor = Store::where('id', $products['store_id'] ?? '')->pluck('lightspeed_vendor_id')->first();
    $brand = Brand::where('id', $products['brand_id'] ?? '')->pluck('lightspeed_brand_id')->first();
  
    $params=[
    "description"=>'#'.$products['id'].'-'.$products['productName'],
    "manufacturerSku"=>'None',
    "customSku"=>$inputs['sku'],
    "quantity"=>$inputs['qty'],
    "tax"=> true,
    "defaultCost"=> $inputs['regular_price'],
    "itemType"=> "box",
    "serialized"=> false,
    "manufacturerID"=>  $brand ?? 0,
    "categoryID"=> $category ?? 0,
    "defaultVendorID"=> $vendor ?? 0,
    "taxClassID"=> "0",
    "seasonID"=> "0",
    "departmentID"=> "0",
    "Prices" =>[
      "ItemPrice"=>[
        "0"=>[
          "amount"=> $inputs['regular_price'],
          "useTypeID"=>1,
          "useType"=>"Default"
        ],
        "1"=>[
          "amount"=> $inputs['sale_price'],
          "useTypeID"=>2,
          "useType"=>"MSRP"
        ]
      ]
        ]
        

    ];
$params['ItemAttributes']=[];
$params['ItemAttributes']['itemAttributeSetID']=$attrbuteId;

if($attributes){
 
  foreach ($attributes as $key => $value) {
    ($inputs[$value]) ? ($params['ItemAttributes']['attribute'.++$key]=$inputs[$value]  ) : '';

  }
}
$params['itemMatrixID']= $matrixId;
$params= json_encode($params);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $params,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
$result= json_decode($response);
if(isset($result->Item->itemID)){
return $result->Item->itemID;
}

  }

  public function storeProductVariationImage($inputs, $url,$token, $productId){
   
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $uri=env('LIGHT_SPEED_URL').$accountID."/Item";
    $url = $uri."/".$productId."/Image.json";
      
    $headers = array(
        'Authorization: Bearer '.$token,
        'Accept: application/json',
        'Content-Type: multipart/form-data');
     
    $imagefile = $inputs; //Full path of image file you wish to use

    $postfields = array(
        'data' => '{
          "description": "Test Image",
          "ordering": 1
      }',
        'image' => new \CURLFile($imagefile, 'text/plain', 'testimage.jpg'));

    $filesize = filesize($imagefile);
    
    $ch = curl_init();

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => true,
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_VERBOSE => 1,
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_INFILESIZE => $filesize,
        CURLOPT_RETURNTRANSFER => true
    ); 

    curl_setopt_array($ch, $options);
    $output = curl_exec($ch);

    curl_close($ch);
 
  }
  public function storeSingleProduct($inputs,$product){
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Item.json";
    $method= 'POST';
    
    $productId= $this->storeProductSingleData($inputs,$url,$method,$lightspeed->access_token,$product);
  
    if($inputs['feature_image'] &&  $productId){
      $this->storeProductVariationImage($inputs['feature_image'],$url,$lightspeed->access_token, $productId);
    }
    if($productId){
      return $productId;
    }
  
  }
  public function storeProductSingleData($inputs,$url,$method,$token,$product){


    $category = Category::where('id',$product['category_id'] ?? '')->pluck('lightspeed_category_id')->first();
    $vendor = Store::where('id', $product['store_id'] ?? '')->pluck('lightspeed_vendor_id')->first();
    $brand = Brand::where('id', $product['brand_id'] ?? '')->pluck('lightspeed_brand_id')->first();
  
    $params=[
    "description"=>'#'.$product['id'].'-'.$product['productName'],
    "manufacturerSku"=>'None',
    "customSku"=>$inputs['sku'],
    "quantity"=>$inputs['qty'],
    "tax"=> true,
    "defaultCost"=> $inputs['real_price'],
    "itemType"=> "default",
    "serialized"=> false,
    "manufacturerID"=>  $brand ?? 0,
    "categoryID"=> $category ?? 0,
    "defaultVendorID"=> $vendor ?? 0,
    "taxClassID"=> "0",
    "seasonID"=> "0",
    "departmentID"=> "0",
    "Prices" =>[
      "ItemPrice"=>[
        "0"=>[
          "amount"=> $inputs['real_price'],
          "useTypeID"=>1,
          "useType"=>"Default"
        ],
        "1"=>[
          "amount"=> $inputs['sale_price'],
          "useTypeID"=>2,
          "useType"=>"MSRP"
        ]
      ]
        ]
        

    ];


$params= json_encode($params);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $params,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
      ),
    ));
    
      $response = curl_exec($curl);
      
      curl_close($curl);
  $result= json_decode($response);
  if(isset($result->Item->itemID)){
  return $result->Item->itemID;
  }
  }
  public function storeTags($tags,$matrixId){
 


    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Item/".$matrixId;
   
    $tagName='';
    foreach ($tags as $key => $value) {
      $tagName .= '<tag>'.$value.'</tag>';
    }
    $param='<?xml version="1.0" encoding="UTF-8" ?><Item> <Tags>'  . $tagName. '</Tags> </Item>'; 
    $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>$param,
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '. $lightspeed->access_token,
          'Content-Type: application/xml'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      
    
     
  }
}