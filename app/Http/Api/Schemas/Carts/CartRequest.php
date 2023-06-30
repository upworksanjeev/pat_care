<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new Cart",
 *     description="Request for create a new cart",
 * )
 */
class CartRequest
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

    /**
     * @OA\Property(
     *     title="Product Id",
     *     description="Product Id for storring",
     *     example="1",
     * )
     *
     * @var string
     */
    public $product_id;

    /**
     * @OA\Property(
     *     title="Quantity",
     *     description="Quantity for storring",
     *     example="2",
     * )
     *
     * @var string
     */
    public $quantity;

      /**
     * @OA\Property(
     *     title="Variation",
     *     description="Variation id for storring",
     *     example="2",
     * )
     *
     * @var string
     */
    public $variation_product_id;
}
