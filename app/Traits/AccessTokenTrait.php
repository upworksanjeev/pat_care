<?php

namespace App\Traits;

use App\Models\LightSpeed;
use Carbon\Carbon;
trait AccessTokenTrait {

  public function accessToken()
  {


          $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
          if(empty($lightspeed->access_token)) {
            $client_secret = env('LIGHT_SPEED_CLIENT_SECRET');
            $client_id = env('LIGHT_SPEED_CLIENT_ID');
            $grant = 'authorization_code';
              $params = [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'code' => 	$lightspeed->code,
                'grant_type' => $grant
              ];
  
  
              $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.lightspeedapp.com/oauth/access_token.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  $params,
              ));
  
              $response = curl_exec($curl);
              curl_close($curl);
              $result=json_decode($response);
            
                  if((isset($result->access_token)) && (!empty($result->access_token))){
                    $lightspeed->update([
                      'access_token'=> $result->access_token,
                      'refresh_token'=> $result->refresh_token,
                       ]);
                  }
                 
          } 
          return true;
         

  }
  public function refreshToken(){

          $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
          if($lightspeed->refresh_token) {
                $client_secret = env('LIGHT_SPEED_CLIENT_SECRET');
                $client_id = env('LIGHT_SPEED_CLIENT_ID');
              
                $params = [
                  'client_id' => $client_id,
                  'client_secret' => $client_secret,
                  'refresh_token' => 	$lightspeed->refresh_token,
                  'grant_type' => 'refresh_token'
                ];
              
                $curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.lightspeedapp.com/oauth/access_token.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>$params,
                  ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response);
            $expired_at= Carbon::now()->addSeconds($result->expires_in);
       
            if(isset($result->access_token)){
         
              $lightspeed->update([
                'access_token'=> $result->access_token,
                'expired_at'=> $expired_at,
                ]);
            }
          }
          return back()->with('success', 'Please Create Access Token first !'); 
  
  }

  public function fetchAccount(){
    $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
    $currentTime=Carbon::now();
  
    if($lightspeed->expired_at < $currentTime){
      $this->refreshToken();
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.lightspeedapp.com/API/Account.json',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$lightspeed->access_token,
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $result=json_decode($response);
 
    if((isset($result->Account->accountID)) && (!empty($result->Account->accountID))){
      $lightspeed->update([
        'account_id'=> $result->Account->accountID,
        ]);
    }
    return true;
  }

}
