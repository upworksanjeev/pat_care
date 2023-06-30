<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Page Response",
 *     description="Page Response",
 * )
 */
class PageResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *   "id": 7,
  *"title": "fgfdgdfgdfs",
  *"slug": "fgfdgdfgdfs",
  *"content": "<p>dfgfdsgsfdgsfdgfdg</p>",
  * "feature_image": "Screenshot_7.png",
  * "status": "1",
  * "user": "Admin",
  * "categories": "admin",
  * "created": "2021-12-10T06:01:07.000000Z",
  * "updated_at": "2021-12-23T06:36:41.000000Z"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
