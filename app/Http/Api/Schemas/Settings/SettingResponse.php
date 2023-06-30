<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="settings Response",
 *     description="settings Response",
 * )
 */
class SettingResponse
{
    
/**
  * @OA\Property(
  * type="object",
  * example={
  *   "site_title": "dfdfdf",
  *      "site_email": "test@gmail.com",
  *      "phone": "1212121",
  *      "logo": null,
  *      "facebook": null,
  *      "youtube": null,
  *      "insta": null,
  *      "linkedin": null,
  *      "copyright": null
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
