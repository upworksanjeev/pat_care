<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Update user profile response",
 *     description="Update user profile response",
 * )
 */
class UpdateProfileRequest
{

/**
     * @OA\Property(
     *     title="name",
     *     description="name of key for updating",
     *     example="random",
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="address",
     *     description="address of key for updating",
     *     example="it park chd",
     * )
     *
     * @var string
     */
    public $address;
     /**
     * @OA\Property(
     *     title="zip_code",
     *     description="zip_code of key for updating",
     *     example="234456",
     * )
     *
     * @var string
     */
    public $zip_code;
     /**
     * @OA\Property(
     *     title="phone",
     *     description="phone of key for updating",
     *     example="9870654321",
     * )
     *
     * @var string
     */
    public $phone;
     /**
     * @OA\Property(
     *     title="city",
     *     description="city of key for updating",
     *     example="chd",
     * )
     *
     * @var string
     */
    public $city;
     /**
     * @OA\Property(
     *     title="state",
     *     description="state of key for updating",
     *     example="punjab",
     * )
     *
     * @var string
     */
    public $state;
     /**
     * @OA\Property(
     *     title="country",
     *     description="country of key for updating",
     *     example="india",
     * )
     *
     * @var string
     */
    public $country;
     /**
     * @OA\Property(
     *     title="profile_image",
     *     description="profile_image of key for updating",
     *     example="random",
     * )
     *
     * @var string
     */
    public $profile_image;

     /**
     * @OA\Property(
     *     title="password",
     *     description="password of key for updating",
     *     example="random",
     * )
     *
     * @var string
     */
    public $password;

}
