<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Categories Response",
 *     description="Categories Response",
 * )
 */
class CategoriesResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *  "id": "3",
  *   "name": "test test",
  *   "slug": "test-test",
  *  "status": "1",
  *  "feature_image": "https://petparent.s3.ap-south-1.amazonaws.com/images/wRPTtNmKj0Kl8NFnUhuoLh5SyHpDXF5C7DmFzbS0.jpg",
  *   "created": "Dec 10, 2021 11:34:05",
  *    "updated_at": "2021-12-10T11:34:05.000000Z",
  *     "childrens": "[]",
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
