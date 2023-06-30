<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductGallery;
use App\Models\VariationAttribute;
use App\Models\VariationAttributeValue;
use App\Models\Category;
use App\Models\Store;
use App\Models\Brand;
use App\Models\LightSpeed;
use Carbon\Carbon;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Models\ProductDescriptionDetail;
use DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Product\AddProduct;
use App\Http\Requests\Admin\Product\UpdateProduct;
use Intervention\Image\Facades\Image;
use Storage;
use App\Traits\AccessTokenTrait;
use App\Traits\ProductTrait;
class ProductController extends Controller
{
    use AccessTokenTrait , ProductTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {
            $data = Product::with('store', 'category')->orderby('id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('status', function ($row)
                            {
                                if ($row->status == 1)
                                {
                                    $status = '<span class="label text-success d-flex">
                                                    <div class="dot-label bg-success me-1"></div>active
                                                </span>';
                                } else
                                {
                                    $status = '<span class="label text-danger d-flex">
                                                    <div class="dot-label bg-danger me-1"></div> inactive
                                                </span>';
                                }

                                return $status;
                            })
                            ->addColumn('action', function ($row)
                            {

                                $action = '
                                <span class="action-buttons">
                                <a  href="' . url("admin/product/duplicate?id=".$row->id) . '" class="btn btn-sm btn-info btn-b"><i class="las la-copy" title="Duplicate Product"></i>
                                </a>
                                    <a  href="' . route("products.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("products.destroy", $row) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this Product?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true)
            ;
        }

        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('type', 'Product')->get();
        $stores = Store::all();
        $brands = Brand::all();

        $attributes = [];
        $variations = [];
        return view('admin.products.addEdit', compact('categories', 'brands','stores', 'attributes', 'variations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddProduct $request)
    {

        $inputs = $request->all();
       
        $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
        $currentTime=Carbon::now();
        if(empty($lightspeed->access_token)){
             $this->accessToken();
        }
            if(empty($lightspeed->account_id)){
                $this->fetchAccount();
        }
        if($lightspeed->expired_at < $currentTime){
            $this->refreshToken();
        }
       
      



        if(!empty($inputs['product_detail'])){
            foreach($inputs['product_detail'] as $key => $value)
            {
                if(empty($value['value'])){
                    unset($inputs['product_detail'][$key]);
                }

            }
        }
        $tags = explode(",", $inputs['tag']);

        // ADD PRODUCT TABLE DATA
        if (!empty($inputs['productName']))
        {
            $products = new Product();
            $products->productName = $inputs['productName'];
            $products->description = $inputs['description'];
            $products->real_price = $inputs['real_price'];
            $products->sale_price = $inputs['sale_price']??0;
           

            if (!empty($inputs['banner_image']))
            {
               
                    $path = Storage::disk('s3')->put('images', $inputs['banner_image']);
                    $image_path = Storage::disk('s3')->url($path);
               

              $products->banner_image = $image_path ?? $inputs['banner_image'] ;

            }
            if (!empty($inputs['feature_image']))
            {
               

                $path = Storage::disk('s3')->put('images', $inputs['feature_image']);
                $image_path = Storage::disk('s3')->url($path);
                
                $products->feature_image = $image_path ?? $inputs['feature_image'];
            }
            $products->about_description = $inputs['about_description'];

            $products->sku = $inputs['sku'];
            $products->weight = $inputs['weight'];
            $products->quantity = $inputs['qty'];
            $products->category_id = $inputs['category_id'];
            $products->brand_id = $inputs['brand_id'];

            $products->store_id = $inputs['store_id'];
            $products->status = $inputs['status'];
            $products->seo_title = $inputs['seo_title'];
            $products->meta_description = $inputs['meta_description'];

            if (!empty($inputs['variations']))
            {
                $products->type = 'Variation';
            }
            $products->save();
            //add attributes
            if(isset($inputs['attributes'])){
                $attrbuteId= $this->checkAttributes($inputs['attributes']);
                $matrixId= $this->storeProductMatrix($attrbuteId,$inputs,$products->id);
            }else{
                $matrixId= $this->storeSingleProduct($inputs,$products);
            }
            if(($products->type = 'Single Product') && (!empty($inputs['tag']))){
                 $this->storeTags($tags,$matrixId);
            }    
           
            $products->matrix_id = $matrixId ?? 0;
            $products->save();
            foreach ($inputs['product_detail'] as $product_detail)
            {

                $productDespImage = new ProductDescriptionDetail();
                if (!empty($product_detail['image_path']))
                {
                 
                        $filename = $product_detail['image_path']->hashname();
                        $image = Image::make($product_detail['image_path'])->resize(600, 600);
                        Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
                        $image_path = Storage::disk('s3')->url('images/'.$filename);
                   
                    $productDespImage->image_path = $image_path;
                }
                $productDespImage->product_id = $products->id;
                $productDespImage->value = $product_detail['value'];
                $productDespImage->save();
            }


            //store images in gallery
            if (!empty($inputs['image']))
            {
                foreach ($inputs['image'] as $image)
                {
                    $productImage = new ProductGallery();
                    $productImage->product_id = $products->id;
                    $productImage->image_path = $image;
                    $productImage->save();
                }
            }
            //tags
            if (!empty($tags))
            {
                foreach ($tags as $vakey => $tagName)
                {

                    $tag = Tag::updateOrCreate([
                                'name' => $tagName
                                    ], [
                                'name' => $tagName
                    ]);

                    $tagValue = new ProductTag;
                    $tagValue->tag_id = $tag->id;
                    $tagValue->product_id = $products->id;
                    $tagValue->save();
                }
            }
            if (!empty($inputs['attributes']))
            {

                $attributeCombinations = [];
                $attributesName = [];
                foreach ($inputs['attributes'] as $vakey => $attributeName)
                {

                    $variationAttribute = VariationAttribute::updateOrCreate([
                                'name' => $vakey
                                    ], [
                                'name' => $vakey
                    ]);
                    array_push($attributesName, $variationAttribute);
                    /* insert attribute */
                    if ($variationAttribute->id)
                    {

                        $variationAttrArrs = explode(",", $attributeName);

                        foreach ($variationAttrArrs as $variationAttrArr)
                        {
                            $variationAttributeValue = new VariationAttributeValue;
                            $variationAttributeValue->attribute_id = $variationAttribute->id;
                            $variationAttributeValue->product_id = $products->id;
                            $variationAttributeValue->name = $variationAttrArr;
                            $variationAttributeValue->save();
                        }
                    }
                }

                if (!empty($inputs['variations']))
                {
                    foreach ($inputs['variations'] as $variation)
                    {
                        $Imagepath = '';
                        if (!empty($variation['image']))
                        {
                          
                                $filename = $variation['image']->hashname();
                                $image = Image::make($variation['image'])->resize(800, 850);
                                Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
                                $Imagepath = Storage::disk('s3')->url('images/'.$filename) ?? $variation['image'];
                            



                        }

                        $productVariation = new ProductVariation;
                        $productVariation->product_id = $products->id;

                        $variationAttributeIds = [];
                        foreach ($attributesName as $key => $attribute)
                        {
                            if($attribute && isset($variation[$attribute->name]))
                            {
                            $selectedAttrubutes = VariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $variation[$attribute->name],'attribute_id' =>$attribute->id])->first();
                            if ($selectedAttrubutes)
                            {
                                $AttributesArray = [];
                                $AttributesArray['attribute_id'] = $selectedAttrubutes->id;
                                $AttributesArray['attribute_name_id'] = $selectedAttrubutes->attribute_id;
                                array_push($variationAttributeIds, $AttributesArray);
                            }
                        }
                        }
                        $productVariation->real_price = $variation['regular_price'];
                        $productVariation->sale_price = $variation['sale_price']??0;

                        $productVariation->quantity = $variation['qty'];
                        $productVariation->weight = $variation['weight'];
                        $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                        $productVariation->sku = $variation['sku'];
                        $productItemId=  $this->storeProductVariation($variation,$products,$matrixId,$attrbuteId);
                        $productVariation->lightspeed_item_id = $productItemId ?? 0;
                        $productVariation->image = $Imagepath;
                        $productVariation->save();
                        $this->storeTags($tags,$productItemId);
                     
                    }
                }
            }
        }

        return redirect('admin/products')->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::where('type', 'Product')->get();
        $stores = Store::all();
        $brands = Brand::all();

        $product = Product::with(['category', 'store', 'productDescriptionDetail', 'productVariation', 'productGallery', 'variationAttributesValue.variationAttributeName'])
                ->where('id', $id)
                ->first();

        $variations = [];
       // dd($product->productVariation->toArray());
        foreach ($product->productVariation as $key => $variation)
        {
            $allvariations = json_decode($variation->variation_attributes_name_id);

            $viewData = [];

            foreach ($allvariations as $data)
            {

                $attr_name = VariationAttribute::where('id', $data->attribute_name_id)->pluck('name')->first();
                $attrValue = VariationAttributeValue::where('id', $data->attribute_id)->pluck('name')->first();
                $viewData[$attr_name] = $attrValue;
            }

            $viewData['Qty'] = array('value' => $variation->quantity, 'name' => 'qty', 'placeholder' => 'Qty', 'type' => 'number', 'customClass' => '');
            $viewData['hidden_id'] = array('value' => $variation->id, 'name' => 'id', 'placeholder' => '', 'type' => 'hidden', 'customClass' => '');
            $viewData['Weight'] = array('value' => $variation->weight, 'name' => 'weight', 'placeholder' => 'weight', 'type' => 'number', 'customClass' => '');
            $viewData['Regular Price'] = array('value' => $variation->real_price, 'name' => 'regular_price', 'placeholder' => 'Regular Price', 'type' => 'number', 'customClass' => '');
            $viewData['Sale Price'] = array('value' => $variation->sale_price, 'name' => 'sale_price', 'placeholder' => 'Sale Price', 'type' => 'number', 'customClass' => '');
            $viewData['Sku'] = array('value' => $variation->sku, 'name' => 'sku', 'placeholder' => 'Sku', 'type' => 'text', 'customClass' => '');
            $viewData['Image(800PX * 850PX)'] = array('value' => '', 'name' => 'image', 'placeholder' => 'Image', 'type' => 'file', 'dataitem' => $variation->image, 'customClass' => 'dropify');

            array_push($variations, $viewData);
        }

        $attributes = [];
        foreach ($product->variationAttributesValue as $data)
        {
            $attributes[$data->variationAttributeName->name][] = $data->name;
        }


        return view('admin.products.addEdit', compact('product','brands', 'stores', 'categories', 'attributes', 'variations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProduct $request, $id)
    {
        $inputs = $request->all();
        if(!empty($inputs['product_detail'])){
            foreach($inputs['product_detail'] as $key => $value)
            {
                if(empty($value['value'])){
                    unset($inputs['product_detail'][$key]);
                }

            }
        }

        if(!isset($inputs['variations'][0]['id'])){
            ProductVariation::where('product_id', $id)->delete();
        }


        $tags = explode(",", $inputs['tag']);
        VariationAttributeValue::where('product_id', $id)->delete();
        if (!empty($inputs['productName']))
        {
            $products = Product::find($id);
            $products->productName = $inputs['productName'];
            $products->description = $inputs['description'];
            $products->real_price = $inputs['real_price'];
            $products->sale_price = $inputs['sale_price']??0;
            $products->sku = $inputs['sku'];
            $products->weight = $inputs['weight'];
            $products->quantity = $inputs['qty'];
            $products->seo_title = $inputs['seo_title'];
            $products->meta_description = $inputs['meta_description'];
            if (!empty($inputs['banner_image']))
            {

                 $path = Storage::disk('s3')->put('images', $inputs['banner_image']);
                $image_path = Storage::disk('s3')->url($path);
                $products->banner_image = $image_path;
            }
            if (!empty($inputs['feature_image']))
            {
                $path = Storage::disk('s3')->put('images', $inputs['feature_image']);
                $image_path = Storage::disk('s3')->url($path);
                $products->feature_image = $image_path;
            }
            $products->about_description = $inputs['about_description'];
            $products->category_id = $inputs['category_id'];
            $products->brand_id = $inputs['brand_id'];

            $products->store_id = $inputs['store_id'];
            $products->status = $inputs['status'];
            if (!empty($inputs['variations']))
            {
                $products->type = 'Variation';
            }
            $products->save();
            //add attributes

            foreach ($inputs['product_detail'] as $product_detail)
            {
                $id = $product_detail['id'] ?? 0;
                $productDespImage = ProductDescriptionDetail::find($id);
                if (!empty($productDespImage))
                {
                    if (!empty($product_detail['image_path']))
                    {
                      $filename = $product_detail['image_path']->hashname();
                        $image = Image::make($product_detail['image_path'])->resize(600, 600);
                        Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
                        $image_path = Storage::disk('s3')->url('images/'.$filename);
                        $productDespImage->image_path = $image_path;
                    }
                    $productDespImage->product_id = $products->id;
                    $productDespImage->value = $product_detail['value'];
                    $productDespImage->save();
                } else
                {
                    $productDespImage = new ProductDescriptionDetail;
                    if (!empty($product_detail['image_path']))
                    {
                        $path = Storage::disk('s3')->put('images', $product_detail['image_path']);
                        $image_path = Storage::disk('s3')->url($path);
                        $productDespImage->image_path = $image_path;
                    }
                    $productDespImage->product_id = $products->id;
                    $productDespImage->value = $product_detail['value'];
                    $productDespImage->save();
                }
            }

            //store images in gallery
            if (!empty($inputs['image']))
            {
                foreach ($inputs['image'] as $image)
                {
                    $productImage = new ProductGallery();
                    $productImage->product_id = $products->id;
                    $productImage->image_path = $image;
                    $productImage->save();
                }
            }
            if (!empty($tags))
            {
                ProductTag::where('product_id', $id)->delete();
                foreach ($tags as $vakey => $tagName)
                {
                    $tag = Tag::updateOrCreate([
                                'name' => $tagName
                                    ], [
                                'name' => $tagName
                    ]);
                    $tagValue = new ProductTag;
                    $tagValue->tag_id = $tag->id;
                    $tagValue->product_id = $products->id;
                    $tagValue->save();
                }
            }

            if (!empty($inputs['attributes']))
            {


                $attributeCombinations = [];
                $attributesName = [];

                foreach ($inputs['attributes'] as $vakey => $attributeName)
                {

                    $variationAttribute = VariationAttribute::updateOrCreate([
                                'name' => $vakey
                                    ], [
                                'name' => $vakey
                    ]);
                    array_push($attributesName, $variationAttribute);

                    /*                     * insert attribute* */
                    if ($variationAttribute->id)
                    {

                        $variationAttrArrs = explode(",", $attributeName);

                        foreach ($variationAttrArrs as $variationAttrArr)
                        {
                            $variationAttributeValue = new VariationAttributeValue;
                            $variationAttributeValue->attribute_id = $variationAttribute->id;
                            $variationAttributeValue->product_id = $products->id;
                            $variationAttributeValue->name = $variationAttrArr;
                            $variationAttributeValue->save();
                        }
                    }
                }

                if (!empty($inputs['variations']))
                {
                    $variationIds =[];
                    foreach ($inputs['variations'] as $variation)
                    {
                        $Imagepath = '';

                        if (!empty($variation['id']))
                        {

                            $productVariation = ProductVariation::find($variation['id']);
                            $productVariation->product_id = $products->id;

                            $variationAttributeIds = [];
                            foreach ($attributesName as $key => $attribute)
                            {

                                if($attribute && $variation[$attribute->name])
                                {
                                    array_push($variationIds,$variation['id']);
                                    $selectedAttrubutes = VariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $variation[$attribute->name],'attribute_id' =>$attribute->id])->first();
                                    if ($selectedAttrubutes)
                                    {
                                        $AttributesArray = [];
                                        $AttributesArray['attribute_id'] = $selectedAttrubutes->id;
                                        $AttributesArray['attribute_name_id'] = $selectedAttrubutes->attribute_id;
                                        array_push($variationAttributeIds, $AttributesArray);
                                    }
                                }

                            }
                            if (!empty($variation['image']))
                            {
                              $filename = $variation['image']->hashname();
                              $image = Image::make($variation['image'])->resize(800, 850);
                              Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
                              $Imagepath = Storage::disk('s3')->url('images/'.$filename);
                                $productVariation->image = $Imagepath;
                            }
                            $productVariation->real_price = $variation['regular_price'];
                            $productVariation->sale_price = $variation['sale_price']??0;

                            $productVariation->quantity = $variation['qty'];
                            $productVariation->weight = $variation['weight'];
                            $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                            $productVariation->sku = $variation['sku'];
                            $productVariation->save();
                        } else
                        {
                            $productVariation = new ProductVariation;
                            $productVariation->product_id = $products->id;

                            $variationAttributeIds = [];

                            foreach ($attributesName as $key => $attribute)
                            {
                                if($attribute && isset($variation[$attribute->name]))
                                {
                                $selectedAttrubutes = VariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $variation[$attribute->name],'attribute_id' =>$attribute->id])->first();
                                if ($selectedAttrubutes)
                                {
                                    $AttributesArray = [];
                                    $AttributesArray['attribute_id'] = $selectedAttrubutes->id;
                                    $AttributesArray['attribute_name_id'] = $selectedAttrubutes->attribute_id;
                                    array_push($variationAttributeIds, $AttributesArray);
                                }
                                }
                            }
                            if (!empty($variation['image']))
                            {
                                $path = Storage::disk('s3')->put('images', $variation['image']);
                                $Imagepath = Storage::disk('s3')->url($path);
                                $productVariation->image = $Imagepath;
                            }
                            $productVariation->real_price = $variation['regular_price'];
                            $productVariation->sale_price = $variation['sale_price']??0;

                            $productVariation->quantity = $variation['qty'];
                            $productVariation->weight = $variation['weight'];
                            $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                            $productVariation->sku = $variation['sku'];
                            $productVariation->save();
                            array_push($variationIds,$productVariation->id);
                        }
                    }

                    ProductVariation::where('product_id',$products->id)->whereNotIn('id', array_unique($variationIds))->delete();
                }
            }
        }

        return back()->with('success', 'Product Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductDescriptionDetail::where('product_id', $id)->delete();
        ProductVariation::where('product_id', $id)->delete();
        ProductGallery::where('product_id', $id)->delete();
        VariationAttributeValue::where('product_id', $id)->delete();
        ProductTag::where('product_id', $id)->delete();
        Product::find($id)->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    public function save_photo(Request $request)
    {


        if ($request->file('images'))
        {
          $filename = $request->images->hashname();
          $image = Image::make($request->images)->resize(800, 850);
          Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
          $path = Storage::disk('s3')->url('images/'.$filename);
            // $path = Storage::disk('s3')->put('images/products', $request->images);
            // $path = Storage::disk('s3')->url($path);
            $id = substr($path, -8, 1);
            return Response()->json([
                        "success" => true,
                        "image" => $path,
                        "id" => $id
            ]);
        }

        return Response()->json([
                    "success" => false,
                    "image" => ''
        ]);
    }

    public function del_photo(Request $request)
    {

        ProductGallery::find($request->id)->delete();

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }

    public function del_variationPhoto(Request $request)
    {

        ProductVariation::find($request->id)->update(['image' => null]);

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }

    public function del_banner_photo(Request $request)
    {

        Product::find($request->id)->update(['banner_image' => null]);

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }

    public function delete_desp_feild(Request $request)
    {

        ProductDescriptionDetail::find($request->id)->delete();

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }

    public function duplicate(Request $request)
    {

        $id=$request->id;

        $product = Product::find($id);
        $productDescriptionDetail = ProductDescriptionDetail::where('product_id',$id)->get();
        $productGallery = ProductGallery::where('product_id',$id)->get();
        $productVariation = ProductVariation::where('product_id',$id)->get();
        $variationAttributeValue = VariationAttributeValue::where('product_id',$id)->get();
        $productTag = ProductTag::where('product_id',$id)->get();

        $products = Product::create([
            'productName' =>  $product->productName . ' copy'.date("d-h-m-s"),
            'description' =>  $product->description,
            'sku' =>  $product->sku,
            'banner_image' =>  $product->banner_image,
            'about_description' =>  $product->about_description,
            'type' =>  $product->type,
            'store_id' =>  $product->store_id,
            'category_id' =>  $product->category_id,
            'brand_id' =>  $product->brand_id,
            'feature_image' =>  $product->feature_image,
            'real_price' =>  $product->real_price,
            'sale_price' =>  $product->sale_price,
            'weight' =>  $product->weight,
            'quantity' =>  $product->quantity,
            'status' =>  $product->status,
            'seo_title' =>  $product->seo_title,
            'meta_description' =>  $product->meta_description,
        ]);
     if(!empty($productDescriptionDetail)){
        foreach ($productDescriptionDetail as $key => $value) {

            ProductDescriptionDetail::create([
                'product_id' =>  $products->id,
                'image_path' =>  $value->image_path,
                'value' =>  $value->value,
            ]);
        }
     }

     if(!empty($productGallery)){
        foreach ($productGallery as $key => $value) {

            ProductGallery::create([
                'product_id' =>  $products->id,
                'image_path' =>  $value->image_path,
            ]);
        }
     }
    if(!empty($productTag)){
            foreach ($productTag as $key => $value) {

                ProductTag::create([
                    'product_id' =>  $products->id,
                    'tag_id' =>  $value->tag_id,
                ]);
            }
        }
    if(!empty($variationAttributeValue)){
        $variationAttributeIds = [];
        foreach ($variationAttributeValue as $key => $value) {

         $selectedAttrubutes=   VariationAttributeValue::create([
                'product_id' =>  $products->id,
                'attribute_id' =>  $value->attribute_id,
                'name' =>  $value->name,
            ]);
            $AttributesArray = [];
            $AttributesArray['attribute_id'] = $selectedAttrubutes->id;
            $AttributesArray['attribute_name_id'] = $selectedAttrubutes->attribute_id;
            array_push($variationAttributeIds, $AttributesArray);
        }
     }

      if(!empty($productVariation)){
            foreach ($productVariation as $key => $value) {

                productVariation::create([
                    'product_id' =>  $products->id,
                    'real_price' =>  $value->real_price,
                    'sale_price' =>  $value->sale_price,
                    'image' =>  $value->image,
                    'weight' =>  $value->weight,
                    'quantity' =>  $value->quantity,
                    'variation_attributes_name_id' =>  json_encode($variationAttributeIds),
                    'sku' =>  $value->sku,
                ]);
            }
         }
    return redirect('admin/products')->with('success', 'Product Duplicate successfully!');
    }
}
