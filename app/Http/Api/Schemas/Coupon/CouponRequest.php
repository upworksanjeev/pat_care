<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Coupon Request response",
 *     description="Coupon Request response",
 * )
 */
class CouponRequest
{

  /**
     * @OA\Property(
     *     title="name",
     *     description="name for storring",
     *     example="TPPSHGN0DJZRHGN0DJZR",
     * )
     *
     * @var string
     */
    public $name;

}
