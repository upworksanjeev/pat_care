<?php

namespace App\Traits;


use App\Models\LightSpeed;
trait VendorTrait {

public function storeVendor($inputs){
 
  $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
  $accountID= $lightspeed->account_id;
  $url=env('LIGHT_SPEED_URL').$accountID."/Vendor.json";
  $method='POST';
 $vendorId= $this->stored($inputs,$url,$method, $lightspeed->access_token);
 if($vendorId){
  return $vendorId;
 }
}
public function updateVendor($inputs,$id){

  if ($id) {
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Vendor/".$id.".json";
    $method='PUT';
  $vendorId= $this->stored($inputs,$url,$method, $lightspeed->access_token);
  if($vendorId){
    return $vendorId;
  }
  }
}


public function stored($inputs,$url,$method,$token){


 $name= $inputs['name'] ?? '';
 $address1= $inputs['address'] ?? '';
 $city= $inputs['city'] ?? '';
 $state= $inputs['state'] ?? '';
 $zip= $inputs['zip_code'] ?? '';
 $country= $inputs['country'] ?? '';
 $website=$inputs['url'] ?? '';

  $params= [
    "name"=>  $name,
    "accountNumber"=> "",
    "priceLevel"=> null,
    "updatePrice"=> true,
    "updateCost"=> true,
    "updateDescription"=> true,
    "shareSellThrough"=> true,
    "Contact"=> [
      "noEmail"=>false,
      "noPhone"=>false,
      "noMail"=>false,
      "Addresses"=> [
        "ContactAddress"=>[
          "address1"=>$address1,
          "address2"=>"",
          "city"=>$city,
          "state"=>$state,
          "zip"=>$zip,
          "country"=> $country,
          "countryCode"=>"",
          "stateCode"=>"",
        ]
        ],
          "Phones"=>[
            "ContactPhone"=> [
              "number" =>"",
              "useType" =>"Work",

            ]
            ],
            "Emails" => "",
            "Websites" => $website
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
    $result=json_decode($response);
     
     if(isset($result->Vendor->vendorID)){
             
      return $result->Vendor->vendorID;
    }
}
public function deleteVendor($id){

  if ($id) {
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Vendor/".$id.".json";
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
      $result=json_decode($response);
     
  }
}

}