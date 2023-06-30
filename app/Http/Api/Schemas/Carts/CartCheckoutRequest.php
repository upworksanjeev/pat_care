<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new checkout",
 *     description="Request for create a new checkout for a cart",
 * )
 */
class CartCheckoutRequest
{
    /**
     * @OA\Property(
     *     title="key",
     *     description="key  for storing",
     *     example="e2e26459499dac9e6361419963e2e42861cc42adb8d6d",
     * )
     *
     * @var string
     */
    public $key;

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
     *     example="2",
     * )
     *
     * @var string
     */
    public $address;

    /**
     * @OA\Property(
     *     title="phone",
     *     description="phone  for storing",
     *     example="2",
     * )
     *
     * @var string
     */
    public $phone;

       /**
     * @OA\Property(
     *     title="zip_code",
     *     description="zip_code  for storing",
     *     example="2",
     * )
     *
     * @var string
     */
    public $zip_code;
       /**
     * @OA\Property(
     *     title="city",
     *     description="city  for storing",
     *     example="2",
     * )
     *
     * @var string
     */
    public $city;
       /**
     * @OA\Property(
     *     title="state",
     *     description="state  for storing",
     *     example="2",
     * )
     *
     * @var string
     */
    public $state;
       /**
     * @OA\Property(
     *     title="country",
     *     description="country  for storing",
     *     example="2",
     * )
     *
     * @var string
     */
    public $country;

    /**
     * @OA\Property(
     *     title="sh_email",
     *     description="sh_email  for storing",
     *     example="example@example.com",
     * )
     *
     * @var string
     */
    public $sh_email;

    /**
     * @OA\Property(
     *     title="sh_name",
     *     description="sh_name  for storing",
     *     example="Sam",
     * )
     *
     * @var string
     */
    public $sh_name;

    /**
     * @OA\Property(
     *     title="sh_city",
     *     description="sh_city  for storing",
     *     example="Chandigarh",
     * )
     *
     * @var string
     */
    public $sh_city;

    /**
     * @OA\Property(
     *     title="sh_state",
     *     description="sh_state  for storing",
     *     example="Chandigarh",
     * )
     *
     * @var string
     */
    public $sh_state;

    /**
     * @OA\Property(
     *     title="sh_address",
     *     description="sh_address  for storing",
     *     example="211 IT Park",
     * )
     *
     * @var string
     */
    public $sh_address;

    /**
     * @OA\Property(
     *     title="sh_country",
     *     description="sh_country  for storing",
     *     example="India",
     * )
     *
     * @var string
     */
    public $sh_country;

    /**
     * @OA\Property(
     *     title="sh_zip_code",
     *     description="sh_zip_code  for storing",
     *     example="134109",
     * )
     *
     * @var string
     */
    public $sh_zip_code;

    /**
     * @OA\Property(
     *     title="sh_phone",
     *     description="sh_phone  for storing",
     *     example="9854689758",
     * )
     *
     * @var string
     */
    public $sh_phone;

    /**
     * @OA\Property(
     *     title="payment_method",
     *     description="payment_method  for storing",
     *     example="Paypal",
     * )
     *
     * @var string
     */
    public $payment_method;

    /**
     * @OA\Property(
     *     title="shippingmethod",
     *     description="shippingmethod  for storing",
     *     example="express",
     * )
     *
     * @var string
     */
    public $shippingmethod;

    /**
     * @OA\Property(
     *     title="remark",
     *     description="remark  for storing",
     *     example="Test remark",
     * )
     *
     * @var string
     */
    public $remark;
    /**
     * @OA\Property(
     *     title="transaction",
     *     description="transaction  for storing",
     *     example="txn_fserrt54gfhhfdfd",
     * )
     *
     * @var string
     */
    public $transaction;
}
