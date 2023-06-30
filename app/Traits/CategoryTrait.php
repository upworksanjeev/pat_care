<?php

namespace App\Traits;


use App\Models\LightSpeed;
use App\Models\Category;
trait CategoryTrait {

  public function storeCategory($inputs){
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Category.json";
    $method='POST';
   $categoryId= $this->stored($inputs,$url,$method);
   if($categoryId){
    return $categoryId;
   }
   
  }
  public function updateCategory($inputs,$category){
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Category/".$category.".json";
    $method='PUT';
   $categoryId= $this->stored($inputs,$url,$method);
   if($categoryId){
    return $categoryId;
   }
   
  }
 
  public function stored($inputs,$url,$method){

          $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
          $name=$inputs['name'] ?? '';
          
         $category= Category::find($inputs['parent']);
         if($category && !empty($category->lightspeed_category_id)){
          $parentID=$category->lightspeed_category_id;
         }else{
          $parentID=0;
         }
          $params= [
            "name"=> $name,
            "fullPathName"=> $name,
            "parentID"=> $parentID,

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
              'Authorization: Bearer '.$lightspeed->access_token,
              'Content-Type: application/json'
            ),
          ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response);
            if(isset($result->Category->categoryID)){
             
              return $result->Category->categoryID;
            }
  }
  public function deleteCategory($category){
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Category/".$category.".json";
   
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'DELETE',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$lightspeed->access_token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
       return $response;
    
  
   
  }

}