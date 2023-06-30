<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Register new user",
 *     description="Request for create a new user",
 * )
 */
class ResgisterRequest
{
    /**
     * @OA\Property(
     *     title="Name",
     *     description="Name of key for storring",
     *     example="random",
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="Email",
     *     description="Email for storring",
     *     example="example@gmail.com",
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *     title="Password",
     *     description="Password for storring",
     *     example="12345678",
     * )
     *
     * @var string
     */
    public $password;
}
