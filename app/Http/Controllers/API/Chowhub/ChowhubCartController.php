<?php

namespace App\Http\Controllers\API\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChowhubCart;
use App\Models\ChowhubProduct;
use App\Models\ChowhubProductVariation;
use App\Models\ChowhubCartItem;
use App\Models\Order;
use App\Http\Resources\Carts\ChowhubCartResource;
use App\Http\Resources\Carts\ChowhubCartItemsResource;
use App\Http\Requests\API\ChowhubCartIdRequest;
use App\Http\Requests\API\CheckoutRequest;
use App\Http\Requests\API\ChowhubCartAddProductRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Shipping;

class ChowhubCartController extends Controller
{

    /**
     * @OA\Get(
     *      path="/chowhub/cart",
     *      operationId="Create Chowhub cart key",
     *      tags={"ChowhubCarts"},
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
            $cart = ChowhubCart::where('user_id', $user->id)->first();
            if ($cart)
            {
                return new ChowhubCartResource($cart);
            }
        }
        $cart = ChowhubCart::create([
                    'key' => md5(uniqid(rand(), true)) . uniqid(),
                    'user_id' => $user->id ?? 0,
        ]);

        return new ChowhubCartResource($cart);
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
     *      path="/chowhub/cart/{id}",
     *      operationId="show Chowhub cart items",
     *      tags={"ChowhubCarts"},
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
    public function show(ChowhubCart $cart, ChowhubCartIdRequest $request)
    {

        if ($cart->key == $request->key)
        {
            $items = ChowhubCartItem::where('cart_id', $cart->id)->with(['product', 'variationProduct'])->get();
            return ChowhubCartItemsResource::collection($items);
        } else
        {

            return response()->json([
                        'message' => 'The Cart key does not match with any cart.',
                            ], 400);
        }
    }

   /**
     * @OA\Post(
     *      path="/chowhub/cartUpdate/{cart}/{itemId}",
     *      operationId="Update Chowhub cart item",
     *      tags={"ChowhubCarts"},
     *     summary="Update cart item",
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
    public function updateCartItem(ChowhubCart $cart, ChowhubCartItem $itemId, ChowhubCartIdRequest $request)
    {
        if ($cart->key == $request->key)
        {

            $itemId->quantity=  $request->quantity ?? $itemId->quantity;
            $itemId->save();

            return response()->json('Cart item has been Updated.', 200);
        } else
        {

            return response()->json([
                        'message' => 'The Cart key does not match with any cart.',
                            ], 400);
        }
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
     *      path="/chowhub/cart/{id}",
     *      operationId="Delete Chowhub cart",
     *      tags={"ChowhubCarts"},
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
    public function destroy(ChowhubCart $cart, ChowhubCartIdRequest $request)
    {

        if ($cart->key == $request->key)
        {
            ChowhubCartItem::where('cart_id', $cart->id)->delete();
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
     *      path="/chowhub/cart/{cart}/{itemId}",
     *      operationId="Delete Chowhub cart item",
     *      tags={"ChowhubCarts"},
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
    public function deleteCartItem(ChowhubCart $cart, ChowhubCartItem $itemId, ChowhubCartIdRequest $request)
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
     *      path="chowhub/cartIdByKey",
     *      operationId="Get Chowhub cart id by key",
     *      tags={"ChowhubCarts"},
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
    public function getCartIDUsingKey(ChowhubCartIdRequest $request)
    {
        $cart = ChowhubCart::where('key', $request->key)->first();
        if ($cart)
        {
            return new ChowhubCartResource($cart);
        }

        return response()->json([
                    'message' => 'The Cart key does not match with any cart.',
                        ], 400);
    }

    /**
     * @OA\Post(
     * * path="/chowhub/cart/{cart}",
     *   tags={"ChowhubCarts"},
     *   summary="Add chowhub Product into cart",
     *   operationId="Chowhub ProductCart",
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
    public function addProducts(ChowhubCart $cart, ChowhubCartAddProductRequest $request)
    {

        $product_id = $request->product_id;
        $quantity = $request->quantity;

        $variation_product_id = $request->variation_product_id ?? 0;
        //Check if the CarKey is Valid
        if ($cart->key == $request->key)
        {
            //Check if the proudct exist or return 404 not found.
            try
            {
                $Product = ChowhubProduct::findOrFail($product_id);
            } catch (ModelNotFoundException $e)
            {
                return response()->json([
                            'message' => 'The Product you\'re trying to add does not exist.',
                                ], 404);
            }

            //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
            $cartItem = ChowhubCartItem::where(['cart_id' => $cart->id, 'product_id' => $product_id, 'variation_product_id' => $variation_product_id])->first();
            if ($cartItem)
            {
                $cartItem->quantity = $quantity;
                ChowhubCartItem::where(['cart_id' => $cart->id, 'product_id' => $product_id, 'variation_product_id' => $variation_product_id])->update(['quantity' => $quantity]);
            } else
            {
                ChowhubCartItem::create(['cart_id' => $cart->id, 'product_id' => $product_id, 'variation_product_id' => $variation_product_id, 'quantity' => $quantity]);
            }

            return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);
        } else
        {

            return response()->json([
                        'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
                            ], 400);
        }
    }



}
