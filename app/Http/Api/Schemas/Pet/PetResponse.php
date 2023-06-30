<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Pet Response",
 *     description="Pet Response",
 * )
 */
class PetResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  *            "id": 1,
  *          "name": "dogu",
 *           "user_id": 1,
  *          "age": 2,
  *          "type": "cat",
   *         "created_at": "2022-01-20T10:01:36.000000Z"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
