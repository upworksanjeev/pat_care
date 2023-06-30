<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Product Request response",
 *     description="Product Request response",
 * )
 */
class ProductRequest
{

  /**
     * @OA\Property(
     *     title="id",
     *     description="id for storring",
     *     example="1",
     * )
     *
     * @var string
     */
    public $id;
   
}
