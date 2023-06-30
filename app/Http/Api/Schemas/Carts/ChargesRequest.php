<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new checkout",
 *     description="Request for create a new checkout for a cart",
 * )
 */
class ChargesRequest
{


    /**
     * @OA\Property(
     *     title="name ",
     *     description="name for storing",
     *     example="john",
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="email",
     *     description="email for storing",
     *     example="john@gmail.com",
     * )
     *
     * @var string
     */
    public $email;

      /**
     * @OA\Property(
     *     title="address",
     *     description="address  for storing",
     *     example="los angle",
     * )
     *
     * @var string
     */
    public $address;



       /**
     * @OA\Property(
     *     title="zip_code",
     *     description="zip_code  for storing",
     *     example="343334",
     * )
     *
     * @var string
     */
    public $zip_code;
       /**
     * @OA\Property(
     *     title="city",
     *     description="city  for storing",
     *     example="chd",
     * )
     *
     * @var string
     */
    public $city;
       /**
     * @OA\Property(
     *     title="state",
     *     description="state  for storing",
     *     example="hp",
     * )
     *
     * @var string
     */
    public $state;
       /**
     * @OA\Property(
     *     title="country",
     *     description="country  for storing",
     *     example="india",
     * )
     *
     * @var string
     */
    public $country;
          /**
     * @OA\Property(
     *     title="product_id",
     *     description="product_id  for storing",
     *     example="2",
     * )
     *
     * @var string
     */
    public $product_id;      /**
    * @OA\Property(
    *     title="strip_token ",
    *     description="strip_token   for storing",
    *     example="9RQo4Vq7b5CZwlsoJGlq4fEWkqg2Kt54LzigE6Y0ov",
    * )
    *
    * @var string
    */
   public $strip_token ;
     /**
   * @OA\Property(
   *     title="amount",
   *     description="amount  for storing",
   *     example="33",
   * )
   *
   * @var string
   */
  public $amount;
   /**
  * @OA\Property(
    *     title="amount",
    *     description="currency  for storing",
    *     example="$",
    * )
    *
    * @var string
    */
   public $currency;

}
