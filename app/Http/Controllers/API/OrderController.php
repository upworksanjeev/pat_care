<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Requests\API\OrderRequest;
use App\Http\Resources\Orders\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    /**
     * @OA\Get(
     *      path="/order",
     *      operationId="get orders",
     *      tags={"Orders"},
     *      security={
     *          {"Bearer": {}},
     *          },
     *     summary="get orders",
     *     @OA\Response(
     *         response="200",
     *         description="get orders",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResponse")
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
        $limit = $request->limit ? $request->limit : 20;
        $orders = Order::where('user_id', $user->id)->with(['shipping', 'user', 'orderItems'])->orderBy('id','DESC')->paginate($limit);
        return OrderResource::collection($orders);
    }

    /**
     * @OA\Get(
     *      path="/order/{order}",
     *      operationId="get order by id",
     *      tags={"Orders"},
     *     security={
     *          {"Bearer": {}},
     *          },
     *     summary="get order by id",
     *       @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="get orders",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResponse")
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
    public function show(Order $order)
    {
        if (!empty($order))
        {
            return new OrderResource($order);
        } else
        {
            return response()->json([
                        'message' => 'The order you\'re trying to view doesn\'t seem to be yours, hmmmm.',
                            ], 403);
        }
    }

}
