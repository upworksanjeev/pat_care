<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Categories Response",
 *     description="Categories Response",
 * )
 */
class ProductResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *  "id": "2",
  *  "name": "test test",
  *  "slug": "test-test",
  *  "status": "1",
  *  "weight": "21.00",
  *  "description": "dfsdfsdfsd",
  *  "sku": "dsfd",
  *  "quantity": "12",
  *  "price": "12.00",
  *  "sale_price": "121.00",
  *  "variation_attributes": "",
  *   "created": "2021-12-13",
  *  "updated_at": "2021-12-13"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
