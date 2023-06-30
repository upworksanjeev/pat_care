<?php

namespace App\Http\Controllers\API\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\Category\CategoryResource;

class ChowhubCategoryController extends Controller
{

    /**
     * @OA\Get(
     *      path="/chowhub/categories",
     *      operationId="Chowhub Categories",
     *      tags={"ChowhubProducts"},
     *      security={
     *          {"Token": {}},
     *          },
     *
     *     summary="Chowhub Categories",
     *     @OA\Response(
     *         response="200",
     *         description="Chowhub Categories",
     *         @OA\JsonContent(ref="#/components/schemas/CategoriesResponse")
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

        $categories = Category::with('childrens')->where(['parent' => 0, 'type' => 'Chowhub'])->orderBy('order')->all();

        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Get(
     *      path="/chowhub/categories/{id}",
     *      operationId="Chowhub Categories By Id",
     * summary="Chowhub_Categories_by_id",
     *      tags={"ChowhubProducts"},
     * security={
     *          {"Token": {}},
     *          },
     *
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Chowhub Categories By Id",
     *     @OA\Response(
     *         response="200",
     *         description="Categories",
     *         @OA\JsonContent(ref="#/components/schemas/CategoriesResponse")
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
    public function category_by_id(Request $request)
    {


        $categories = ChowhubCategory::with('childrens')->find($request->id);
        if ($categories)
        {
            return new CategoryResource($categories);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invailed Id"]);
        }
    }

}
