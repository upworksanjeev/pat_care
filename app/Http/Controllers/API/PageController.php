<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Setting;
use App\Http\Resources\Admin\PageResource;

class PageController extends Controller
{

    /**
     * @OA\Get(
     *      path="/pages",
     *      operationId="Pages",
     *      tags={"Pages"},
     *      security={
     *          {"Token": {}},
     *          },
     *
     *     summary="Pages",
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/PageResponse")
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
        $header = $request->header('Token');
        $setting = Setting::orderBy('id', 'asc')->first();
        $token = $setting->oauth_token ?? '';
        if ($header == $token)
        {
            $limit = $request->limit ? $request->limit : 20;
            $pages = Post::with(['users', 'categories'])->where('status', 1)->orderBy('id','DESC')->paginate($limit);
            //   print_r($pages);die;
            return PageResource::collection($pages);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invalid Token"]);
        }
    }

    /**
     * @OA\Get(
     *      path="/pages/{id}",
     *      operationId="Pages By Id",
     * summary="Page_by_id",
     *      tags={"Pages"},
     *      security={
     *          {"Token": {}},
     *          },
     *
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Page By Id",
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/PageResponse")
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
    public function pageByID(Request $request, $id)
    {

        $header = $request->header('Token');
        $setting = Setting::orderBy('id', 'asc')->first();
        $token = $setting->oauth_token ?? '';
        if ($header == $token)
        {
            $limit = $request->limit ? $request->limit : 20;
            $pages = Post::with(['users', 'categories'])->where(['status' => 1, 'id' => $id])->orwhere('slug', $id)->first();
            if ($pages)
            {
                return new PageResource($pages);
            } else
            {
                return response()->json(['success' => false, 'message' => "Invalid Id/Slug"]);
            }
        } else
        {

            return response()->json(['success' => false, 'message' => "Invalid Token"]);
        }
    }

}
