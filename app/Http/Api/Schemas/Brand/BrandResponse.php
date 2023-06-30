<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Brand Response",
 *     description="Brand Response",
 * )
 */
class BrandResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
 * "id": 2,
 *           "name": "test test",
 *           "logo": "https://petparent.s3.ap-south-1.amazonaws.com/images/fkBJ1vhxMQ0UM6gmL9F4aXnFQiQ2t4ckdLJ5Qh7p.jpg",
 *           "cover_image": "https://petparent.s3.ap-south-1.amazonaws.com/images/n889KiAXfHpXXziIvCrDqxoiZxkWlumz4iWfyw2W.png",
 *           "brand_color": "3434",
 *           "tag_line": "<p>fdggdf</p>",
  *          "overview": "<p>gfd</p>",
  *          "category_text": "<p>gfdgf</p>"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
