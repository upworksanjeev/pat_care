<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Create new checkout",
 *     description="Request for create a new checkout for a cart",
 * )
 */
class FaqRequest
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
     *     title="email ",
     *     description="email for storing",
     *     example="john",
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *     title="question",
     *     description="question for storing",
     *     example="What is the meaning of Lorem ipsum?",
     * )
     *
     * @var string
     */
    public $question;
   /**
     * @OA\Property(
     *     title="answer",
     *     description="answer for storing",
     *     example="its a dummy data",
     * )
     *
     * @var string
     */
    public $answer;
    /**
     * @OA\Property(
     *     title="product_id",
     *     description="question for product",
     *     example="2",
     * )
     *
     * @var string
     */
    public $product_id;
    /**
     * @OA\Property(
     *     title="published",
     *     description="question for product",
     *     example="1/0",
     * )
     *
     * @var string
     */
    public $published;

}
