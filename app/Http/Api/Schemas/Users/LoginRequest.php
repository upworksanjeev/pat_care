<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Login user",
 *     description="Request for login user",
 * )
 */
class LoginRequest
{
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
