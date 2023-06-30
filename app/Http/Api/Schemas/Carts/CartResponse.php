<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Cart Response",
 *     description="Cart Response",
 * )
 */
class CartResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *  "id": "2",
  * "key": "a88483e72092f83baee46349fef80e9661cc3e5d4cba2"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
