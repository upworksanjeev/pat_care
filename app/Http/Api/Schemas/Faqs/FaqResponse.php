<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Faq Response",
 *     description="Faq Response",
 * )
 */
class FaqResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  *           "id": 1,
  *          "title": "<p>dfsdfs</p>",
  *          "description": "<p>dsfsddf</p>",
  *          "published": 1,
   *         "created_at": "2022-01-20T10:01:36.000000Z"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
