<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Cart Response",
 *     description="Cart Response",
 * )
 */
class SingleCartResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *   "id": 6,
   * "user_id": 0,
   * "key": "e2e26459499dac9e6361419963e2e42861cc42adb8d6d",
  *  "created_at": "2021-12-29T11:12:45.000000Z",
  *  "updated_at": "2021-12-29T11:12:45.000000Z"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
