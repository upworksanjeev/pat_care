<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Coupon Response",
 *     description="Coupon Response",
 * )
 */
class CouponResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  *       "id": 1,
  *  "name": "test test",
  *  "code": "TPPSHGN0DJZRHGN0DJZR",
  *  "category_id": 1,
  *  "product_id": 6,
  *  "value": 13,
  *  "count": 12,
  *  "expired_at": "2022-01-20",
  *  "type": "percentage",
  *  "apply_to": "specific_product"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
