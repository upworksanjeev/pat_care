<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\VariationAttribute;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\AttributesResource;
use App\Http\Requests\API\ProductRequest;

class ProductController extends Controller
{

    /**
     * @OA\Get(
     *      path="/products",
     *      operationId="Products",
     *      tags={"Products"},
     *      security={
     *          {"Token": {}},
     *          },
     *     summary="Products",
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

        $data = $request->get('search');
        if (!empty($data))
        {
            $search_product = Product::with(['productVariation', 'variationAttributesValue', 'tags.tagName'])->where('productName', 'like', "%{$data}%")
                    ->orWhere('sku', 'like', "%{$data}%")
                    ->orWhere('type', 'like', "%{$data}%")
                    ->orWhere('store_id', 'like', "%{$data}%")
                    ->orWhere('category_id', 'like', "%{$data}%")
                    ->orWhere('weight', 'like', "%{$data}%")
                    ->orWhereHas('tags.tagName', function ($query) use ($data)
                    {

                        $query->where('name', 'like', "%{$data}%");
                        $query->where('tag_id', 'like', "%{$data}%");
                    })
                   -> orderBy('id','DESC')
                    ->paginate($limit);

            return ProductResource::collection($search_product);
        }

        $products = Product::with(['category', 'productDescriptionDetail', 'store', 'productVariation', 'productGallery', 'variationAttributesValue', 'tags.tagName'])->orderBy('id','DESC')->paginate($limit);

        return ProductResource::collection($products);
    }

    /**
     * @OA\Get(
     *      path="/products/{id}",
     *      operationId="Product By Id",
     * summary="products_by_id",
     *      tags={"Products"},
     *      security={
     *          {"Token": {}},
     *          },
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Products By Id",
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


        $products = Product::with(['category', 'productDescriptionDetail', 'store', 'productVariation', 'productGallery', 'variationAttributesValue'])->find($id);
     
        if ($products)
        {
            return new ProductResource($products);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invalid Id"]);
        }
    }

    /**
     * @OA\Get(
     *      path="/products/category/{id}",
     *      operationId="Product By categoryId",
     * summary="product_by_categoryid",
     *      tags={"Products"},
     *      security={
     *          {"Token": {}},
     *          },
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Products By Category Id",
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
    public function productByCategoryId(Request $request, $id)
    {
        $limit = $request->limit ? $request->limit : 20;
        $data = $request->get('search');
        if (!empty($data))
        {
            $search_product = Product::with(['tags.tagName'])->where('productName', 'like', "%{$data}%")
                    ->orWhere('sku', 'like', "%{$data}%")
                    ->orWhere('type', 'like', "%{$data}%")
                    ->orWhere('store_id', 'like', "%{$data}%")
                    ->orWhere('category_id', 'like', "%{$data}%")
                    ->orWhere('weight', 'like', "%{$data}%")
                    ->orWhereHas('tags.tagName', function ($query) use ($data)
                    {

                        $query->where('name', 'like', "%{$data}%");
                        $query->where('tag_id', 'like', "%{$data}%");
                    })
                    ->orderBy('id','DESC')
                    ->paginate($limit);

            return ProductResource::collection($search_product);
        }
        $products = Product::with(['category', 'store', 'productVariation', 'productGallery', 'variationAttributesValue'])->where('category_id', $id)->orderBy('id','DESC')->paginate($limit);

        if ($products)
        {
            return ProductResource::collection($products);
        } else
        {
            return response()->json(['success' => false, 'message' => "Invalid Id"]);
        }
    }

    /**
     * @OA\Get(
     *      path="/products/attributes/{id}",
     *      operationId="Product By categoryId",
     * summary="attributes_by_product_id",
     *      tags={"Products"},
     *      security={
     *          {"Token": {}},
     *          },
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="1",
     *         required=true,
     *      ),
     *     summary="Attributes by Product Id",
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
        $attributes = VariationAttribute::whereHas('variationAttributeName', function ($query) use ($id)
                {
                    return $query->where('product_id', '=', $id);
                })
                ->get();

        return AttributesResource::collection($attributes);
    }

}
