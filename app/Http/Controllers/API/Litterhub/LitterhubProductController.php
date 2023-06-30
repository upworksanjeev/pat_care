<?php

namespace App\Http\Controllers\API\Litterhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Litterhub\LitterhubProduct;
use App\Models\Litterhub\LitterhubTag;
use App\Models\Litterhub\LitterhubVariationAttribute;
use App\Http\Resources\Products\LitterhubProductResource;
use App\Http\Resources\Products\AttributesResource;
use App\Http\Resources\Products\LitterhubTagResource;

class LitterhubProductController extends Controller
{

    /**
     * @OA\Get(
     *      path="/litterhub/products",
     *      operationId="litterhub Products",
     *      tags={"LitterhubProducts"},
     *     summary="litterhub Products",
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
        $limit = $request->limit ? $request->limit : 20;
        $products = LitterhubProduct::with([ 'store', 'productVariation', 'productDescriptionImage', 'productGallery', 'variationAttributesValue', 'tags.tagName'])->orderBy('id','DESC')->paginate($limit);

        return LitterhubProductResource::collection($products);
    }

    /**
     * @OA\Get(
     *      path="/litterhub/products/{id}",
     *      operationId="litterhub Product By Id",
     * summary="litterhub_products_by_id",
     *      tags={"LitterhubProducts"},
     *      security={
     *          {"Token": {}},
     *          },
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Chowhub Products By Id",
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


        $products = LitterhubProduct::with([ 'store', 'productVariation', 'productDescriptionImage', 'productGallery', 'variationAttributesValue'])->find($id);
        if ($products)
        {
            return new LitterhubProductResource($products);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invalid Id"]);
        }
    }



    /**
     * @OA\Get(
     *      path="/litterhub/products/attributes/{id}",
     *      operationId="litterhub Product By categoryId",
     * summary="attributes_by_product_id",
     *      tags={"LitterhubProducts"},
     *      security={
     *          {"Token": {}},
     *          },
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Attributes by litterhub Product Id",
     *     @OA\Response(
     *         response="200",
     *         description="products",
     *         @OA\JsonContent(ref="#/components/schemas/AttributesResponse")
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
    public function getAttributeByProduct(Request $request, $id)
    {
        $attributes =LitterhubVariationAttribute::whereHas('variationAttributeName', function ($query) use ($id)
                {
                    return $query->where('product_id', '=', $id);
                })->get();

        return AttributesResource::collection($attributes);
    }

    /**
     * @OA\Get(
     *      path="/litterhub/tags",
     *      operationId="litterhub tags",
     *      tags={"Litterhubtags"},
     *     summary="litterhub tags",
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

        $tags = LitterhubTag::all();

        return LitterhubTagResource::collection($tags);
    }

}
