<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Order Response",
 *     description="Order Response",
 * )
 */
class OrderResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  * "id": 20,
  *          "transaction_id": "cf7d5a4a4bf880fdbd132ac36981d13b",
  *          "status": "pending",
  *          "grand_total": 414,
  *          "item_count": 1,
  *          "is_paid": 0,
  *          "payment_method": "cod",
  *          "shippingmethod": "efsdfsdfsd",
  *          "remark": "dgfdgfd",
  *          "created_at": "2022-01-19T11:54:26.000000Z",
  *          "user": {
  *              "id": 1,
   *             "name": "admin",
  *              "profile_image": null,
  *              "email": "admin@admin.com",
  *              "address": "rgdrtrgr",
   *             "zip_code": "34234",
  *              "phone": "45543",
  *              "city": "56",
   *             "state": "56gfd",
  *              "country": "6cvbgg",
   *             "created_at": "Dec 27, 2021 05:31:41"
  *          },
  *          "shipping": {
  *              "id": 4,
  *              "sh_name": "dgdsg",
  *              "sh_address": "cvdfgsdfsd",
  *              "sh_city": "gdfg",
  *              "sh_state": "dfsd",
  *              "sh_country": "fdsdf",
   *             "sh_zip_code": "535343",
   *             "sh_phone": "455435",
  *              "sh_email": "admin@admin.com",
  *              "created_at": "2022-01-19T11:51:14.000000Z"
  *          },
  *          "orderItems": 
  *              {
   *                 "id": 4,
   *                 "product_id": 2,
    *                "variation_id": 4,
    *                "unit_price": "450",
   *                 "quantity": 4,
   *                 "product": {
   *                     "id": 2,
   *                     "name": "fdgfdg,",
   *                     "feature_image": "https://petparent.s3.ap-south-1.amazonaws.com/images/Qp4jWuExff3k3FxbQw3Ool4K2WAYLPiWTu3UAlvy.png"
    *                },
   *                 "variationProduct": {
    *                    "id": 4,
    *                    "real_price": 435,
    *                    "sale_price": 450,
    *                    "image": "https://petparent.s3.ap-south-1.amazonaws.com/images/LnVMgn0L7dwX8ZyVRaCjDUeQYO53aogE702daYVW.png"
    *                },
  *                  "total_price": "1800",
  *                  "created_at": "2022-01-19T11:54:26.000000Z"
  *              }
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
