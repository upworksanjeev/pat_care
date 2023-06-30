<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Profile Response",
 *     description="Profile Response",
 * )
 */
class ProfileResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  * "name": "Bob",
  *  "email": "example@gmail.com",
  *  "address": "fdgdfg",
   * "zip_code": "34234",
   * "phone": "45543",
  *  "city": "56",
  *  "state": "56gfd",
  *  "country": "6cvbgg",
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
