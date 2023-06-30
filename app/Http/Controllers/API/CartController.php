<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\CartItem;
use App\Models\Order;
use App\Http\Resources\Carts\CartResource;
use App\Http\Resources\Carts\CartItemsResource;
use App\Http\Requests\API\CartIdRequest;
use App\Http\Requests\API\CheckoutRequest;
use App\Http\Requests\API\ChargesRequest;
use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\Charge;

use App\Http\Requests\API\CartAddProductRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\LightSpeed;
use Carbon\Carbon;

use App\Traits\AccessTokenTrait;
use App\Traits\OrderTrait;

class CartController extends Controller
{
    use AccessTokenTrait , OrderTrait;
    /**
     * @OA\Get(
     *      path="/cart",
     *      operationId="Create cart key",
     *      tags={"Carts"},
     *     summary="Create cart key",
     *     @OA\Response(
     *         response="200",
     *         description="Create cart key",
     *         @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        if ($user)
        {
            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart)
            {
                return new CartResource($cart);
            }
        }
        $cart = Cart::create([
                    'key' => md5(uniqid(rand(), true)) . uniqid(),
                    'user_id' => $user->id ?? 0,
        ]);

        return new CartResource($cart);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *      path="/cart/{id}",
     *      operationId="show cart items",
     *      tags={"Carts"},
     *     summary="show cart items",
     *        *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *      *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartKeyRequest")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="show cart items",
     *       @OA\JsonContent(ref="#/components/schemas/SingleCartResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Cart $cart, CartIdRequest $request)
    {
        if ($cart->key == $request->key)
        {
            $items = CartItem::where('cart_id', $cart->id)->with(['product.variationAttributesValue', 'variationProduct'])->get();

            return CartItemsResource::collection($items);
        } else
        {

            return response()->json([
                        'message' => 'The Cart key does not match with any cart.',
                            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *      path="/cart/{id}",
     *      operationId="Delete cart",
     *      tags={"Carts"},
     *     summary="Delete cart",
     *        *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartKeyRequest")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Get cart id by key",
     *
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cart $cart, CartIdRequest $request)
    {

        if ($cart->key == $request->key)
        {
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->delete();
            return response()->json('Cart has been deleted.', 204);
        } else
        {

            return response()->json([
                        'message' => 'The Cart key does not match with any cart.',
                            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *      path="/cart/{cart}/{itemId}",
     *      operationId="Delete cart item",
     *      tags={"Carts"},
     *     summary="Delete cart item",
     *        *      @OA\Parameter(
     *         name="cart id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     * *        *      @OA\Parameter(
     *         name="item id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartKeyRequest")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Delete cart item by key",
     *
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCartItem(Cart $cart, CartItem $itemId, CartIdRequest $request)
    {
        if ($cart->key == $request->key)
        {

            $itemId->delete();

            return response()->json('Cart item has been deleted.', 204);
        } else
        {

            return response()->json([
                        'message' => 'The Cart key does not match with any cart.',
                            ], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/cartIdByKey",
     *      operationId="Get cart id by key",
     *      tags={"Carts"},
     *     summary="Get cart id by key",
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartKeyRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Get cart id by key",
     *         @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartIDUsingKey(CartIdRequest $request)
    {
        $cart = Cart::where('key', $request->key)->first();
        if ($cart)
        {
            return new CartResource($cart);
        }

        return response()->json([
                    'message' => 'The Cart key does not match with any cart.',
                        ], 400);
    }

    /**
     * @OA\Post(
     * * path="/cart/{cart}",
     *   tags={"Carts"},
     *   summary="Add Product into cart",
     *   operationId="ProductCart",
     * *        *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartRequest")
     *     ),
     *    @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * */

    /**
     * Add Product into cart api
     *
     * @return \Illuminate\Http\Response
     */
    public function addProducts(Cart $cart, CartAddProductRequest $request)
    {


            $inputs=$request->all();
        //Check if the CarKey is Valid

        if ($cart->key == $inputs['key'])
        {
            //Check if the proudct exist or return 404 not found.
            try
            {
                $Product = Product::findOrFail($inputs['product_id']);
            } catch (ModelNotFoundException $e)
            {
                return response()->json([
                            'message' => 'The Product you\'re trying to add does not exist.',
                                ], 404);
            }

                        //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
                        $cartItem = CartItem::where(['cart_id' => $cart->id, 'product_id' => $inputs['product_id'], 'variation_product_id' => $inputs['variation_product_id']])->first();
                        if (!empty($cartItem))
                        {
                            $updatequantity = $cartItem->quantity + $inputs['quantity'];
                            $cartItem->update(['quantity' =>  $updatequantity]);
                        } else
                        {
                            CartItem::create(['cart_id' => $cart->id, 'product_id' => $inputs['product_id'], 'variation_product_id' => $inputs['variation_product_id'], 'quantity' => $inputs['quantity']]);
                        }




            return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);
        } else
        {

            return response()->json([
                        'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
                            ], 400);
        }

    }
/**
     * @OA\Post(
     * * path="/cart/update/{cart}",
     *   tags={"Carts"},
     *   summary="update cart Product into cart",
     *   operationId="ProductCart",
     * *        *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartRequest")
     *     ),
     *    @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * */

    /**
     * Add Product into cart api
     *
     * @return \Illuminate\Http\Response
     */

    public function updateProducts(Cart $cart, Request $request){

        $cartitems     = $request->cartitems;
        $cartErrors    = [];
        $productErrors = [];

        foreach ($cartitems as  $cartitem) {
            if ($cart->key == $cartitem['key']){
                $Product = Product::find($cartitem['product_id']);
                if($Product) {
                    $cartItem = CartItem::where(['cart_id' => $cart->id, 'product_id' => $cartitem['product_id'], 'variation_product_id' => $cartitem['variation_product_id']])->first();

                    CartItem::updateOrCreate(['cart_id' => $cart->id, 'product_id' => $cartitem['product_id'], 'variation_product_id' => $cartitem['variation_product_id']], ['quantity' => $cartitem['quantity']]);
                } else {
                    array_push($productErrors, $cartitem['product_id']. ' The Product you\'re trying to add does not exist.');
                }
            } else {
                array_push($cartErrors, $cartitem['key'].'The CarKey you provided does not match the Cart Key for this Cart.');
            }
        }
        if(count($cartErrors) || count($productErrors)) {
            return response()->json([
                'cartError' => $cartErrors,
                'productErrors' => $productErrors,
                'status' => 'False'
                    ], 400);
        }
        return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);

    }
    /**
     * @OA\Post(
     * * path="/checkout/{cart}",
     *   tags={"Carts"},
     *   summary="Add checkout for order from cart",
     *   operationId="CheckOutCart",
     * *        *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CartCheckoutRequest")
     *     ),
     *    @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * */

    /**
     * Add Product into cart api
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Cart $cart, CheckoutRequest $request)
    {   
       
        if ($cart->key == $request->key)
        {
           
            $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
            $currentTime=Carbon::now();
            if(empty($lightspeed->access_token)){
                 $this->accessToken();
            }
                if(empty($lightspeed->account_id)){
                    $this->fetchAccount();
            }
            if($lightspeed->expired_at < $currentTime){
                $this->refreshToken();
              }
    
            $user = auth('api')->user();
            $stripToken= $request->strip_token;
            if (!$user)
            {
                $roleGuest = Role::where(['name' => 'Guest'])->first();
                $user = User::updateOrCreate(
                                [
                                    'email' => $request->email,
                                ],
                                [
                                    'name' => $request->name,
                                    'address' => $request->address,
                                    'zip_code' => $request->zip_code,
                                    'state' => $request->state,
                                    'city' => $request->city,
                                    'country' => $request->country,
                                    'phone' => $request->phone,
                                    'password' => bcrypt(uniqid(rand(), true))
                ]);
                $user->assignRole($roleGuest);
            }


            $totalPrice = (float) 0.0;
            $grand_total = 0;
            $sub_total = 0;

            $items = $cart->items;

            $shipping = Shipping::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'sh_email' => $request->sh_email,
                            ],
                            [
                                'sh_name' => $request->sh_name,
                                'sh_city' => $request->sh_city,
                                'sh_state' => $request->sh_state,
                                'sh_address' => $request->sh_address,
                                'sh_country' => $request->sh_country,
                                'sh_zip_code' => $request->sh_zip_code,
                                'sh_phone' => $request->sh_phone,
                            ]
            );

            $order = Order::create([
                        'grand_total' => $grand_total,
                        'sub_total' => $sub_total,
                        'item_count' => count($items),
                        'remark' => $request->remark,
                        'user_id' => $user->id ?? 0,
                        'payment_method' => $request->payment_method,
                        'shippingmethod' => $request->shippingmethod,
                        'shipping_id' => $shipping->id ?? 0,
            ]);
        
            $orderItemID= $this->storeOrder($items,$request->remark);
            foreach ($items as $item)
            {
               
                $product_id = $item->product_id;
                $variation_id = $item->variation_product_id;

                $quantity = 0;
                $price = 0;
                if ($item->variation_product_id != 0)
                {
                    $productvariation = ProductVariation::find($variation_id);

                    $quantity = $item->quantity;
                    $unitPrice = $productvariation->sale_price;
                    $lightspeedItemId=$productvariation->lightspeed_item_id ;
                    $totalPrice = $unitPrice * $quantity ?? 0;
                } else
                {

                    $product = Product::find($product_id);
                    $quantity = $item->quantity;
                    $unitPrice = $product->sale_price;
                    $totalPrice = $unitPrice * $quantity ?? 0;
                    $lightspeedItemId=$product->matrix_id ;
                }
               
             if($orderItemID){
                $this->createOrder($orderItemID,$lightspeed->access_token,$item,$totalPrice,$unitPrice,$lightspeedItemId);
             }
                $order_item = OrderItem::updateOrCreate(
                                [
                                    'order_id' => $order->id,
                                    'product_id' => $product_id,
                                    'variation_id' => $variation_id,
                                    'unit_price' => $unitPrice,
                                    'total_price' => $totalPrice,
                                    'quantity' => $quantity,
                ]);
            }

            $order->grand_total = $order->grand_total + $totalPrice;
            $order->sub_total = $order->sub_total + $totalPrice;

            if (!empty($request->code))
            {
                $coupon = Coupon::where('code', $request->code)->first();
                $currentdate = date('Y-m-d');
                if (!empty($coupon))
                {
                    if ($coupon->count > 0 && $coupon->expired_at > $currentdate)
                    {
                        $code = $coupon->value;
                        $type = $coupon->type;
                    } else
                    {
                        return response()->json(['success' => false, 'message' => "Coupon is expired"], 400);
                    }
                } else
                {
                    return response()->json(['success' => false, 'message' => "Coupon is not valid"], 400);
                }
            }

            if (!empty($code))
            {
                if ($type = 'percentage')
                {
                    $coupon_discount = $order->grand_total * $code / 100;
                    $order->grand_total = $order->grand_total - $coupon_discount;
                    $order->discount = $coupon_discount;

                } else
                {

                    $order->grand_total = $order->grand_total - $code;
                    $order->discount = $code;
                }
                $coupon->count = $coupon->count - 1;
                $coupon->save();
            }
            $order->save();

            $order->transaction_id = $request->transaction ?? null;
            $order->lightspeedOrderId = $orderItemID ?? null;

            $order->save();


            return response()->json([
                        'message' => 'Order created successfully',

                            ], 200);
        } else
        {
            return response()->json([
                        'message' => 'The Key you provided does not match the Cart Key for this Cart.',
                            ], 400);
        }
    }
        /**
     * @OA\Post(
     * * path="/payment",
     *   tags={"Carts"},
     *   summary="Add payment for order from cart",
     *   operationId="payment",
     *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChargesRequest")
     *     ),
     *      @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\JsonContent(ref="#/components/schemas/ChargesResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * */
    public function payment(ChargesRequest $request)
    {


            $user = auth('api')->user();
            $stripToken= $request->strip_token;
            $amount= $request->amount;
            $product_id= $request->product_id;

            if (!$user)
            {
                $roleGuest = Role::where(['name' => 'Guest'])->first();
                $user = User::updateOrCreate(
                                [
                                    'email' => $request->email ?? null,
                                ],
                                [
                                    'name' => $request->name ?? null,
                                    'address' => $request->address ?? null,
                                    'zip_code' => $request->zip_code ?? null,
                                    'state' => $request->state ?? null,
                                    'city' => $request->city ?? null,
                                    'country' => $request->country ?? null,
                                    'phone' => $request->phone ?? null,
                                    'password' => bcrypt(uniqid(rand(), true))
                ]);
                $user->assignRole($roleGuest);
            }
            if(isset($product_id)){
                $product = Product::find($product_id);
            }
            // stripe payment integration
            if(isset($stripToken)){
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $customer = Customer::create(array(
                    'name' => $request->name,
                    'email' => $request->email,
                    'source' => $stripToken,
                    "address" => ["city" =>$request->city ?? null,
                    "country" => $request->country ?? null,
                    "line1" => $request->address ?? null,
                    "postal_code" => $request->zip_code ?? null,
                    "state" => $request->state ?? null]
                ));
                $charge = Charge::create(array(
                        'customer' => $customer->id,
                        'amount'   => $amount ?? null,
                        'currency' => $request->currency ?? null,
                        'description' =>  $product->productName ?? null,
                ));
                return response()->json([
                    'message' => 'Payment Successfull',
                    'transaction_id' => $charge['balance_transaction'],
                    'amount' => $charge['amount']
                        ], 200);
            }



    }
}
