<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe;
class TestController extends Controller
{
  public function index(){

 Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $key = env('STRIPE_SECRET');

    $stripe = new \Stripe\StripeClient(
        $key
      );
//    $product=   $stripe->products->create([
//         'name' => 'Gold Special',
//         'description' => 'testing description',

//         'unit_label' => '45',
//         'tax_code' => 'txcd_99999999'
//       ]);
//      $charge= $stripe->balanceTransactions->retrieve(
//         'txn_3KQQQZSHhSBkVxnb19YQjHhN',
//         []
//       );
    $token=  $stripe->tokens->create([
        'card' => [
          'number' => '4242424242424242',
          'exp_month' => 2,
          'exp_year' => 2023,
          'cvc' => '314',
        ],
      ]);
      print($token);die;
      $customer = \Stripe\Customer::create(array(
        'name' => 'test',

        'email' => 'murali@jytra.com',
        'source' => $token['id'],
        "address" => ["city" => "hyd", "country" => "india", "line1" => "adsafd werew", "postal_code" => "500090", "state" => "telangana"]
    ));


    $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => '100',
            'currency' => 'inr',
            'description' => "TrueCAD 2021 Premium",
    ));

    print_r($charge);die();
  }
}
