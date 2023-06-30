<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new checkout",
 *     description="Request for create a new checkout for a cart",
 * )
 */
class RatingRequest
{
    /**
     * @OA\Property(
     *     title="product_id",
     *     description="product_id  for storing",
     *     example="1",
     * )
     *
     * @var string
     */
    public $product_id;

    /**
     * @OA\Property(
     *     title="rating ",
     *     description="rating for storing",
     *     example="123",
     * )
     *
     * @var string
     */
    public $rating;
/**
     * @OA\Property(
     *     title="title",
     *     description="title for storing",
     *     example="title",
     * )
     *
     * @var string
     */
    public $title;
    /**
     * @OA\Property(
     *     title="description",
     *     description="description for storing",
     *     example="description",
     * )
     *
     * @var string
     */
    public $description;
      /**
     * @OA\Property(
     *     title="email ",
     *     description="email  for storing",
     *     example="user@gmail.com",
     * )
     *
     * @var string
     */
    public $email ;
     /**
     * @OA\Property(
     *     title="status ",
     *     description="status  for storing",
     *     example="0/1",
     * )
     *
     * @var string
     */
    public $status ;
      /**
     * @OA\Property(
     *     title="images ",
     *     description="images  for storing",
     *     example="user.jpg",
     * )
     *
     * @var string
     */
    public $images ;

}
