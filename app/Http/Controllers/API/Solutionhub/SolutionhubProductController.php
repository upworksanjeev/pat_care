<?php

namespace App\Http\Controllers\Api\Solutionhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solutionhub\SolutionhubProduct;
use App\Models\Category;

use App\Models\Solutionhub\SolutionhubTag;
use App\Models\Solutionhub\SolutionhubProductTag;
use App\Http\Requests\Admin\Solutionhub\Product\AddProduct;
use App\Http\Requests\Admin\Solutionhub\Product\UpdateProduct;
use App\Http\Resources\Products\SolutionhubProductResource;
use App\Http\Resources\Products\AttributesResource;
use App\Http\Resources\Products\SolutionhubTagResource;


class SolutionhubProductController extends Controller
{

    /**
     * @OA\Get(
     *      path="/solutionhub/products",
     *      operationId="Solutionhub Products",
     *      tags={"SolutionhubProducts"},
     *     summary="Solutionhub Products",
     *         security={
     *          {"Token": {}},
     *          },
     *     @OA\Response(
     *         response="200",
     *         description="Products",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResponse")
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
        // $limit = $request->limit ? $request->limit : 20;
        $products = SolutionhubProduct::with( ['tags.tagName','problemCategory','category','solutionCategory'])->orderBy('id','DESC')->get();
        // $products->parent_category = Category::where('type','Solutionhub')->where('parent',0)->get();
   
        return SolutionhubProductResource::collection($products);
    }

    /**
     * @OA\Get(
     *      path="/solutionhub/products/{id}",
     *      operationId="Solutionhub Product By Id",
     * summary="Solutionhub_products_by_id",
     *      tags={"SolutionhubProducts"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Solutionhub Products By Id",
     *     @OA\Response(
     *         response="200",
     *         description="products",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResponse")
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
    public function productById(Request $request, $id)
    {


        $products = SolutionhubProduct::find($id);
        if ($products)
        {
            return new SolutionhubProductResource($products);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invalid Id"]);
        }
    }




    /**
     * @OA\Get(
     *      path="/solutionhub/tags",
     *      operationId="Solutionhub tags",
     *      tags={"SolutionhubProducts"},
     *     summary="Solutionhub tags",
     *         security={
     *          {"Token": {}},
     *          },
     *     @OA\Response(
     *         response="200",
     *         description="tags",
     *         @OA\JsonContent(ref="#/components/schemas/TagResponse")
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
    public function allTags(Request $request)
    {

        $tags = SolutionhubTag::all();

        return SolutionhubTagResource::collection($tags);
    }

}
