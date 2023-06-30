<?php

namespace App\Traits;


use App\Models\LightSpeed;
use App\Models\Order;
use App\Models\Product;

trait OrderTrait {


  public function storeOrder($items,$notes){

    
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $accountID= $lightspeed->account_id;
    $url=env('LIGHT_SPEED_URL').$accountID."/Order.json";
    $method='POST';
    $product = Product::with('store')->where('id',$items[0]['product_id'] ?? '')->first();
    
    $venderId= $product['store']['lightspeed_vendor_id'] ?? 0;
    $orderId= $this->generateOrder($venderId,$url,$method,$lightspeed->access_token,$notes);
    
    if($orderId){
     
      return $orderId;
    }
    

  }

  public function generateOrder($vender,$url,$method,$token,$notes){ 
  
      $params=[
        "orderedDate"=>date('Y-m-d', time()),
        "shipInstructions" => $notes,
        "stockInstruction" => $notes,
        "vendorID" => $vender,
        "shopID" => '1',

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
                  'Content-Type: application/json',
                  'Authorization: Bearer '.$token
                ),
              ));

              $response = curl_exec($curl);

              curl_close($curl);
              $result= json_decode($response);

            if(isset($result->Order->orderID)){
              return $result->Order->orderID;
            }

  }
  public function createOrder($orderId,$token,$items,$totalPrice,$unitPrice,$lightspeedItemId)
  {
        $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
        $accountID= $lightspeed->account_id;
        $url=env('LIGHT_SPEED_URL').$accountID."/OrderLine.json";
          $params=[
            "quantity"=> $items['quantity'],
            "price"=> $unitPrice,
            "originalPrice"=> $unitPrice,
            "numReceived"=> "0",
            "total"=> $totalPrice,
            "itemID"=> $lightspeedItemId,
            "orderID"=> $orderId,

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
                CURLOPT_POSTFIELDS =>$params,
                CURLOPT_HTTPHEADER => array(
                  'Authorization: Bearer '.$token,
                  'Content-Type: application/json'
                ),
              ));

              $response = curl_exec($curl);

              curl_close($curl);
            
  }

}