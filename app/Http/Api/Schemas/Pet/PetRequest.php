<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new checkout",
 *     description="Request for create a new checkout for a cart",
 * )
 */
class PetRequest
{
    /**
     * @OA\Property(
     *     title="name",
     *     description="name  for storing",
     *     example="johm",
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="type ",
     *     description="type for storing",
     *     example="dog/cat",
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *     title="image",
     *     description="image for storing",
     *     example="https://petparent.s3.ap-south-1.amazonaws.com/images/4Qpw6AjW7C0PT3GhfLsqctckMeOchNwe2BDFgcW9.png",
     * )
     *
     * @var string
     */
    public $image;

    /**
     * @OA\Property(
     *     title="age",
     *     description="age for pet",
     *     example="2",
     * )
     *
     * @var string
     */
    public $age;

}
