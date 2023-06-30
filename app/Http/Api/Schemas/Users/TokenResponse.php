<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="token response",
 *     description="token response",
 * )
 */
class TokenResponse
{

    /**
      * @OA\Property(
      * type="object",
      * example={
    
      *  "client_id": "2",
      *  "client_secret": "eyJ0eXAiOiJKVhJkpDuW_NQgUE4ccUTXVKXWrHhYL2Q88ncLi",
      *  }
      * ),
      * )
     * @var array
     */
    public $data;
}
