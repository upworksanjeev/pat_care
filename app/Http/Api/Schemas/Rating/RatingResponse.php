<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="RatingResponse",
 *     description="RatingResponse",
 * )
 */
class RatingResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  *         "id": 1,
  *          "title": null,
  *          "description": "dfgdfdsfsdfsdf",
  *          "user": {
  *              "id": 6,
  *              "name": "edf",
  *              "email": "admin@fdgdfgfdgfdg.com",
  *              "address": null,
  *              "zip_code": null,
  *              "phone": null,
  *              "city": null,
  *              "state": null,
  **              "country": null,
   *             "created_at": "Jan 20, 2022 10:55:41"
   *         },
  *          "product": null,
  *          "published": null,
  **          "created_at": "2022-01-20T12:45:56.000000Z"
   *     
  **  }
  * ),
  * )
 * @var array
 */
    public $data;
}
