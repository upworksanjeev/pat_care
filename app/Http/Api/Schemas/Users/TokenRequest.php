<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Register new user",
 *     description="Request for create a new user",
 * )
 */
class TokenRequest
{
    /**
     * @OA\Property(
     *     title="client_id",
     *     description="client_id of key for storring",
     *     example="2",
     * )
     *
     * @var string
     */
    public $client_id;

    /**
     * @OA\Property(
     *     title="client_secret",
     *     description="client_secret for storring",
     *     example="8BSSg7qMYw2NAJaiMhQOCYxGlFSs141SLfPRLU",
     * )
     *
     * @var string
     */
    public $client_secret;

  
}
