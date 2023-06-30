<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="store Response",
 *     description="store Response",
 * )
 */
class StoreResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *  "id": 1,
  *          "name": "nike"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
