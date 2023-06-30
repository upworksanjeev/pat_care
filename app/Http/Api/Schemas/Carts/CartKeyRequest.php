<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new Cart",
 *     description="Request for create a new cart",
 * )
 */
class CartKeyRequest
{
    /**
     * @OA\Property(
     *     title="key",
     *     description="key  for storring",
     *     example="e2e26459499dac9e6361419963e2e42861cc42adb8d6d",
     * )
     *
     * @var string
     */
    public $key;

   
}
