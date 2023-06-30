<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Products\ProductResource;
class BrandController extends Controller
{
      /**
     * @OA\Get(
     *      path="/brand/{id}",
     *      operationId="brands",
     *      tags={"Brand"},
     *
     *     summary="Brand",
     *   *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/BrandResponse")
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

    public function index($id)
    {
       
        $brands = Brand::find($id);
        if($brands){
            return  new BrandResource($brands);

        }
       
    }

    /**
     * @OA\Get(
     *      path="/brand/product/{brand_id}",
     *      operationId="get products by brands",
     *      tags={"Brand"},
     *
     *     summary="Brand",
     *   *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="3",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
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
    public function productByBrand($id)
    {
        $products = Product::with(['category', 'productDescriptionDetail', 'store', 'productVariation', 'productGallery', 'variationAttributesValue'])->where('brand_id', $id)->get();
        if ($products)
        {
            return ProductResource::collection($products);
        }
    }


    public function chouhubIndex($id,Request $request)
    {
      
    }


  
   
}
