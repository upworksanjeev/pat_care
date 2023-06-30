<?php

namespace App\Http\Controllers\API\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChowhubStore;
use App\Http\Resources\Stores\StoreResource;

class ChowhubStoreController extends Controller
{

    /**
     * @OA\Get(
     *      path="/chowhub/stores",
     *      operationId="chowhub stores",
     *      tags={"ChowhubStores"},
     *
     *     summary="Chowhub stores",
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/StoreResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $stores = ChowhubStore::orderBy('id', 'DESC')->get();
        return StoreResource::collection($stores);
    }

    /**
     * @OA\Get(
     *      path="/chowhub/stores/{store}",
     *      operationId="chowhub store By Id",
     * summary="store by id",
     *      tags={"ChowhubStores"},
     *
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Chowhub store By Id",
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/StoreResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ChowhubStore $store)
    {
        return new StoreResource($store);
    }

}
