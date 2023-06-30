<?php

namespace App\Traits;


use App\Models\LightSpeed;


trait BrandTrait {


    public function storeBrand($inputs){
      
    
     $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
     $accountID= $lightspeed->account_id;
     $url=env('LIGHT_SPEED_URL').$accountID."/Manufacturer.json";
     $method='POST';
    $brandId= $this->stored($inputs,$url,$method,$lightspeed->access_token);
    if($brandId){
     return $brandId;
    }

    }
    public function updateBrand($inputs,$id){

      if ($id) {
        $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
        $accountID= $lightspeed->account_id;
        $url=env('LIGHT_SPEED_URL').$accountID."/Manufacturer/".$id.".json";
        $method='PUT';
       $this->stored($inputs,$url,$method, $lightspeed->access_token);
     
      }
    }
    public function deleteBrand($id){

      if ($id) {
        $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
        $accountID= $lightspeed->account_id;
        $url=env('LIGHT_SPEED_URL').$accountID."/Manufacturer/".$id.".json";
       
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
            'Authorization: Bearer '.$lightspeed->access_token,
            'Content-Type: application/json'
          ),
        ));
          $response = curl_exec($curl);
          curl_close($curl);
         
         
      }
    }
    public function stored($inputs,$url,$method,$token){
    
          $name= $inputs['name'] ?? '';
          $params=[
          "name" => $name
          ];
          $params = json_encode($params);
  
     
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
        $result = json_decode($response);
       
        if (isset($result->Manufacturer->manufacturerID)) {
          return $result->Manufacturer->manufacturerID;
        }

    }
  }