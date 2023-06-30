<?php

/**
 * @OA\Schema(
 *     type="object",
 *     title="Charges Response",
 *     description="Charges Response",
 * )
 */
class ChargesResponse
{

/**
  * @OA\Property(
  * type="object",
  * example={
  *  "message": "payment successfull",
  * "transaction_id": "txn_46349fef80e9661cc3e5d4cba2",
  * "amount": "45"
  *  }
  * ),
  * )
 * @var array
 */
    public $data;
}
