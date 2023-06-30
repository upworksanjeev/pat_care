<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="OverAllRatingResponse",
 *     description="OverAllRatingResponse",
 * )
 */
class OverAllRatingResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={

  *      "success": true,
  *  "overAllRating": 3,
  *  "total-reviews": 6
   *
  **  }
  * ),
  * )
 * @var array
 */
    public $data;
}
