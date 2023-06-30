<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Http\Resources\Coupon\CouponResource;
use App\Http\Requests\API\CouponRequest;

class CouponController extends Controller
{

    /**
     * @OA\post(
     *      path="/coupon",
     *      operationId="coupon",
     *      tags={"coupon"},
     *
     *     summary="coupon",
     *   *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CouponRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/CouponResponse")
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
    public function index(CouponRequest $request)
    {
        $coupon = Coupon::where('code', $request->name)->first();
        $currentdate = date('Y-m-d');
        if (!empty($coupon))
        {
            if ($coupon->count > 0 && $coupon->expired_at > $currentdate)
            {
                return new CouponResource($coupon);
            } else
            {
                return response()->json(['success' => false, 'message' => "Coupon is expired"], 400);
            }
        } else
        {
            return response()->json(['success' => false, 'message' => "Coupon is not valid"], 400);
        }
    }

}
