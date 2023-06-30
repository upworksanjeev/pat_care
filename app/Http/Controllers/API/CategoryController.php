<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Solutionhub\SolutionHubCategory;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SolutionhubCategoryResource;

use App\Http\Requests\API\CategoriesRequest;

class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *      path="/categories",
     *      operationId="Categories",
     *      tags={"Products"},
     *      security={
     *          {"Token": {}},
     *          },
     *
     *     summary="Categories",
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
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 20;
        $categories = Category::with('childrens')->where(['status' => 1, 'type' => 'Product'])->orderBy('order')->paginate($limit);

        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Get(
     *      path="/categories/{id}",
     *      operationId="Categories By Id",
     * summary="Categories_by_id",
     *      tags={"Products"},
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
     *     summary="Categories By Id",
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

        $limit = $request->limit ? $request->limit : 20;
        $categories = Category::with('childrens')->paginate($limit)->find($request->id);
        if ($categories)
        {
            return new CategoryResource($categories);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invailed Id"]);
        }
    }
      /**
     * @OA\Get(
     *      path="solutionhub/categories",
     *      operationId="Categories",
     *      tags={"SolutionhubProducts"},
     *      security={
     *          {"Token": {}},
     *          },
     *
     *     summary="Categories",
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
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function solution_category(Request $request)
    {
        // $limit = $request->limit ? $request->limit : 20;
        $categories = SolutionHubCategory::with('problem','solution')->get();
       
        return SolutionhubCategoryResource::collection($categories);
    }
        /**
     * @OA\Get(
     *      path="solutionhub/categories/{id}",
     *      operationId="Categories By Id",
     * summary="Categories_by_id",
     *      tags={"Products"},
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
     *     summary="Categories By Id",
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
    public function solutionhubcategory_by_id($id)
    {

        
        $categories = Category::with('childrens')->find($id);
        if ($categories)
        {
            return new CategoryResource($categories);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invailed Id"]);
        }
    }
}
