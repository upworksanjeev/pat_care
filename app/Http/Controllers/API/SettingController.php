<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Resources\Settings\SettingResource;

class SettingController extends Controller
{

    /**
     * @OA\Get(
     *      path="/settings",
     *      operationId="settings",
     *      tags={"Settings"},
     *    
     *     summary="settings",
     *     @OA\Response(
     *         response="200",
     *         description="settings",
     *         @OA\JsonContent(ref="#/components/schemas/SettingResponse")
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

        $setting = Setting::orderBy('id', 'asc')->first();
        return new SettingResource($setting);
    }

}
