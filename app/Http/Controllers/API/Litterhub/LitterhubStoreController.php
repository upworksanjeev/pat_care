<?php

namespace App\Http\Controllers\API\Litterhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Litterhub\LitterhubStore;
use App\Http\Resources\Stores\StoreResource;

class LitterhubStoreController extends Controller
{

    /**
     * @OA\Get(
     *      path="/litterhub/stores",
     *      operationId="litterhub stores",
     *      tags={"LitterhubStores"},
     *
     *     summary="litterhub stores",
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
        $stores = LitterhubStore::orderBy('id', 'asc')->get();
        return StoreResource::collection($stores);
    }

    /**
     * @OA\Get(
     *      path="/litterhub/stores/{store}",
     *      operationId="Litterhub store By Id",
     * summary="store by id",
     *      tags={"LitterhubStores"},
     *
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="litterhub store By Id",
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
    public function show(LitterhubStore $store)
    {
        return new StoreResource($store);
    }

}
