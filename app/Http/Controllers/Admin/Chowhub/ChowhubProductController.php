<?php

namespace App\Http\Controllers\Admin\Chowhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChowhubProduct;
use App\Models\ChowhubProductVariation;
use App\Models\ChowhubProductGallery;
use App\Models\ChowhubVariationAttribute;
use App\Models\ChowhubVariationAttributeValue;
use App\Models\Category;
use App\Models\ChowhubTag;
use App\Models\ChowhubBrand;
use App\Models\ChowhubProductBrand;

use App\Models\ChowhubBackendTag;
use App\Models\ChowhubProductBackendTag;
use App\Models\ChowhubProductTag;
use App\Models\ChowhubProductDescriptionImage;
use App\Models\ChowhubProductFeaturePageImage;
use App\Models\ChowhubStore;
use DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Chowhub\Product\AddProduct;
use App\Http\Requests\Admin\Chowhub\Product\UpdateProduct;
use Storage;
use Intervention\Image\Facades\Image;

class ChowhubProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {
    
            if(!empty($request['order'])){
                $data = ChowhubProduct::with('store')->get();
            }else{
                $data = ChowhubProduct::with('store')->orderby('id','DESC');
            }
           
            

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($row)
                            {
                             
                                    $checkbox = '<input type="checkbox" name="export" id="" value="'.$row->id.'" class="form-check-input checkbox">';
                                
                                return $checkbox;
                            })
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
                                $action = '<span class="action-buttons">
                                <a  href="' . url("admin/chowhub-product/duplicate?id=".$row->id) . '" class="btn btn-sm btn-info btn-b"><i class="las la-copy" title="Duplicate Product"></i>
                                </a>
                                <a  href="' . route("chowhub-products.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("chowhub-products.destroy", $row) . '"
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
                            ->rawColumns(['action', 'status','checkbox'])
                            ->make(true)
            ;
        }

        return view('admin.chowhub.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $stores = ChowhubStore::all();
        $attributes = [];
        $variations = [];
        return view('admin.chowhub.products.addEdit', compact('stores', 'attributes', 'variations'));
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
        $tags = explode(",", $inputs['tag']);
        $backendtags = explode(",", $inputs['backend_tag']);
        $brands = explode(",", $inputs['brand']);

        // ADD PRODUCT TABLE DATA


        if (!empty($inputs['productName']))
        {
            $age=$inputs['age']??[];
            $protein_type=$inputs['protein_type']??[];
            $weight=$inputs['weight']??[];

            $products = new ChowhubProduct();
            $products->productName = $inputs['productName'];
            $products->description = $inputs['description'];
            $products->real_price = $inputs['real_price'];
            $products->sale_price = $inputs['sale_price'] ??0;
            $products->sku = $inputs['sku'];
            $products->pet_type = $inputs['pet_type'];
            $products->age =json_encode($age);
            $products->food_type = $inputs['food_type'];
            $products->protein_type = json_encode($protein_type) ;
            $products->weight = json_encode($weight);
            if (!empty($inputs['feature_image']))
            {
                $path = Storage::disk('s3')->put('images', $inputs['feature_image']);
                $image_path = Storage::disk('s3')->url($path);
                $products->feature_image = $image_path;
            }
            $products->quantity = $inputs['qty'];

            $products->store_id = $inputs['store_id'];
            $products->status = $inputs['status'];
            if (!empty($inputs['variations']))
            {
                $products->type = 'Variation';
            }
            $products->save();
            //add attributes
            //store images in gallery
            if (!empty($inputs['image']))
            {
                foreach ($inputs['image'] as $image)
                {
                    $productImage = new ChowhubProductGallery();
                    $productImage->product_id = $products->id;
                    $productImage->image_path = $image;
                    $productImage->save();
                }
            }
// feature page image
            if (!empty($inputs['feature_page_images']))
            {
                foreach ($inputs['feature_page_images'] as $key => $image)
                {

                    ChowhubProductFeaturePageImage::create([
                        'product_id' => $products->id,
                        'priority' => $key,
                        'image_path' => $image
                    ]);
                }
            }
            //store desp images
            if (!empty($inputs['description_images']))
            {
                foreach ($inputs['description_images'] as $key => $image)
                {


                    ChowhubProductDescriptionImage::create([
                        'image_path' => $image,
                        'product_id' => $products->id,
                        'priority' => $key,
                    ]);
                }
            }
            //tags
            if (!empty($tags))
            {
                foreach ($tags as $vakey => $tagName)
                {

                    $tag = ChowhubTag::updateOrCreate([
                                'name' => trim(strtolower($tagName))
                                    ], [
                                'name' => trim(strtolower($tagName))
                    ]);

                    $tagValue = new ChowhubProductTag;
                    $tagValue->tag_id = $tag->id;
                    $tagValue->product_id = $products->id;
                    $tagValue->save();
                }
            }
            if (!empty($backendtags))
            {
                foreach ($backendtags as $vakey => $tagName)
                {

                    $tag = ChowhubBackendTag::updateOrCreate([
                                'name' => trim(strtolower($tagName))
                                    ], [
                                'name' => trim(strtolower($tagName))
                    ]);

                    $tagValue = new ChowhubProductBackendTag;
                    $tagValue->tag_id = $tag->id;
                    $tagValue->product_id = $products->id;
                    $tagValue->save();
                }
            }
            if (!empty($brands))
            {
                foreach ($brands as $vakey => $brandName)
                {

                    $brand = ChowhubBrand::updateOrCreate([
                                'name' => $brandName
                                    ], [
                                'name' => $brandName
                    ]);

                    $brandValue = new ChowhubProductBrand;
                    $brandValue->brand_id = $brand->id;
                    $brandValue->product_id = $products->id;
                    $brandValue->save();
                }
            }
            if (!empty($inputs['attributes']))
            {

                $attributeCombinations = [];
                $attributesName = [];
                foreach ($inputs['attributes'] as $vakey => $attributeName)
                {

                    $variationAttribute = ChowhubVariationAttribute::updateOrCreate([
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
                            $variationAttributeValue = new ChowhubVariationAttributeValue;
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
                            $image = Image::make($variation['image'])->resize(360, 360);
                            Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
                            $Imagepath = Storage::disk('s3')->url('images/'.$filename);
                        }

                        $productVariation = new ChowhubProductVariation;
                        $productVariation->product_id = $products->id;

                        $variationAttributeIds = [];
                        foreach ($attributesName as $key => $attribute)
                        {
                            if($attribute && isset($variation[$attribute->name]))
                            {
                            $selectedAttrubutes = ChowhubVariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $variation[$attribute->name],'attribute_id' =>$attribute->id])->first();

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
                        $productVariation->sale_price = $variation['sale_price']  ??0;

                        $productVariation->quantity = $variation['qty'];
                        $productVariation->weight = $variation['weight'];
                        $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                        $productVariation->sku = $variation['sku'];

                        $productVariation->image = $Imagepath;
                        $productVariation->save();
                    }
                }
            }
        }

        return redirect('admin/chowhub-products')->with('success', 'Product added successfully!');
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

        $stores = ChowhubStore::all();
        $product = ChowhubProduct::with(['category', 'store', 'productVariation', 'productFeaturePageImage', 'productGallery', 'variationAttributesValue.variationAttributeName'])->where('id', $id)->first();

        $variations = [];
        foreach ($product->productVariation as $key => $variation)
        {
            $allvariations = json_decode($variation->variation_attributes_name_id);

            $viewData = [];

            foreach ($allvariations as $data)
            {

                $attr_name = ChowhubVariationAttribute::where('id', $data->attribute_name_id)->pluck('name')->first();
                $attrValue = ChowhubVariationAttributeValue::where('id', $data->attribute_id)->pluck('name')->first();
                $viewData[$attr_name] = $attrValue??'';
            }

            $viewData['Qty'] = array('value' => $variation->quantity, 'name' => 'qty', 'placeholder' => 'Qty', 'type' => 'number', 'customClass' => '');
            $viewData['Weight'] = array('value' => $variation->weight, 'name' => 'weight', 'placeholder' => 'weight', 'type' => 'number', 'customClass' => '');
            $viewData['Regular Price'] = array('value' => $variation->real_price, 'name' => 'regular_price', 'placeholder' => 'Regular Price', 'type' => 'number', 'customClass' => '');
            $viewData['Sale Price'] = array('value' => $variation->sale_price, 'name' => 'sale_price', 'placeholder' => 'Sale Price', 'type' => 'number', 'customClass' => '');
            $viewData['Sku'] = array('value' => $variation->sku, 'name' => 'sku', 'placeholder' => 'Sku', 'type' => 'text', 'customClass' => '');
            $viewData['Image(360px*360px)'] = array('value' => '', 'name' => 'image', 'placeholder' => 'Image', 'type' => 'file', 'dataitem' => $variation->image, 'customClass' => 'dropify');
            $viewData['hidden_id'] = array('value' => $variation->id, 'name' => 'id', 'placeholder' => '', 'type' => 'hidden', 'customClass' => '');

            array_push($variations, $viewData);
        }

        $attributes = [];
        foreach ($product->variationAttributesValue as $data)
        {
            $attributes[$data->variationAttributeName->name][] = $data->name;
        }

        return view('admin.chowhub.products.addEdit', compact('product', 'stores',  'attributes', 'variations'));
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



        if(!isset($inputs['variations'][0]['id'])){
            ChowhubProductVariation::where('product_id', $id)->delete();
        }
        ChowhubVariationAttributeValue::where('product_id', $id)->delete();
        $tags = explode(",", $inputs['tag']);
        $backendtags = explode(",", $inputs['backend_tag']);
        $brands = explode(",", $inputs['brand']);

        if (!empty($inputs['productName']))
        {
            $age=$inputs['age']??[];
            $protein_type=$inputs['protein_type']??[];
            $weight=$inputs['weight']??[];
            $products = ChowhubProduct::find($id);
            $products->productName = $inputs['productName'];
            $products->description = $inputs['description'];
            $products->real_price = $inputs['real_price'];
            $products->sale_price = $inputs['sale_price'] ??0;
            if (!empty($inputs['feature_image']))
            {
                $path = Storage::disk('s3')->put('images', $inputs['feature_image']);
                $image_path = Storage::disk('s3')->url($path);
                $products->feature_image = $image_path;
            }
            $products->sku = $inputs['sku'];

            $products->pet_type = $inputs['pet_type'];
            $products->weight = json_encode($weight);
            $products->age =json_encode($age);
            $products->food_type = $inputs['food_type'];
            $products->protein_type = json_encode($protein_type);
            $products->quantity = $inputs['qty'];
            $products->store_id = $inputs['store_id'];
            $products->status = $inputs['status'];
            if (!empty($inputs['variations']))
            {
                $products->type = 'Variation';
            }
            $products->save();

            ChowhubProductFeaturePageImage::where('product_id', $products->id)->delete();
            ChowhubProductDescriptionImage::where('product_id', $products->id)->delete();
            //add attributes
            if (!empty($tags))
            {
                ChowhubProductTag::where('product_id', $id)->delete();
                foreach ($tags as $vakey => $tagName)
                {
                    $tag = ChowhubTag::updateOrCreate([
                                'name' => trim(strtolower($tagName))
                                    ], [
                                'name' => trim(strtolower($tagName))
                    ]);
                    $tagValue = new ChowhubProductTag;
                    $tagValue->tag_id = $tag->id;
                    $tagValue->product_id = $products->id;
                    $tagValue->save();
                }
            }
            if (!empty($backendtags))
            { ChowhubProductBackendTag::where('product_id', $id)->delete();
                foreach ($backendtags as $vakey => $tagName)
                {

                    $tag = ChowhubBackendTag::updateOrCreate([
                                'name' => trim(strtolower($tagName))
                                    ], [
                                'name' => trim(strtolower($tagName))
                    ]);

                    $tagValue = new ChowhubProductBackendTag;
                    $tagValue->tag_id = $tag->id;
                    $tagValue->product_id = $products->id;
                    $tagValue->save();
                }
            }
            if (!empty($brands))
            {
                ChowhubProductBrand::where('product_id', $id)->delete();
                foreach ($brands as $vakey => $brandName)
                {

                    $brand = ChowhubBrand::updateOrCreate([
                                'name' => $brandName
                                    ], [
                                'name' => $brandName
                    ]);

                    $brandValue = new ChowhubProductBrand;
                    $brandValue->brand_id = $brand->id;
                    $brandValue->product_id = $products->id;
                    $brandValue->save();
                }
            }
            if (!empty($inputs['feature_page_images']))
            {
                foreach ($inputs['feature_page_images'] as $key => $image)
                {

                    ChowhubProductFeaturePageImage::create([
                        'product_id' => $products->id,
                        'priority' => $key,
                        'image_path' => $image
                    ]);
                }
            }
            //store images in gallery
            if (!empty($inputs['image']))
            {
                foreach ($inputs['image'] as $image)
                {
                    $productImage = new ChowhubProductGallery();
                    $productImage->product_id = $products->id;
                    $productImage->image_path = $image;
                    $productImage->save();
                }
            }
            //store desp images
            if (!empty($inputs['description_images']))
            {
                foreach ($inputs['description_images'] as $key => $image)
                {


                    ChowhubProductDescriptionImage::create([
                        'image_path' => $image,
                        'product_id' => $products->id,
                        'priority' => $key,
                    ]);
                }
            }
            if (!empty($inputs['attributes']))
            {


                $attributeCombinations = [];
                $attributesName = [];
                foreach ($inputs['attributes'] as $vakey => $attributeName)
                {

                    $variationAttribute = ChowhubVariationAttribute::updateOrCreate([
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
                            $variationAttributeValue = new ChowhubVariationAttributeValue;
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

                            $productVariation = ChowhubProductVariation::find($variation['id']);
                            $productVariation->product_id = $products->id;

                            $variationAttributeIds = [];
                            foreach ($attributesName as $key => $attribute)
                            {

                                    if($attribute && isset($variation[$attribute->name]))
                                    {
                                            array_push($variationIds,$variation['id']);
                                            $selectedAttrubutes = ChowhubVariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $variation[$attribute->name],'attribute_id' =>$attribute->id])->first();
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
                                $image = Image::make($variation['image'])->resize(360, 360);
                                Storage::disk('s3')->put('/images/'.$filename, $image->stream(), 'public');
                                $Imagepath = Storage::disk('s3')->url('images/'.$filename);
                                $productVariation->image = $Imagepath;
                            }
                            $productVariation->real_price = $variation['regular_price'];
                            $productVariation->sale_price = $variation['sale_price']  ??0;

                            $productVariation->quantity = $variation['qty'];
                            $productVariation->weight = $variation['weight'];
                            $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                            $productVariation->sku = $variation['sku'];
                            $productVariation->save();
                        } else
                        {
                            $productVariation = new ChowhubProductVariation;
                            $productVariation->product_id = $products->id;

                            $variationAttributeIds = [];
                            foreach ($attributesName as $key => $attribute)
                            {
                                if($attribute && isset($variation[$attribute->name]))
                                {
                                        $selectedAttrubutes = ChowhubVariationAttributeValue::select('id', 'attribute_id')->where(['product_id' => $products->id, 'name' => $variation[$attribute->name],'attribute_id' =>$attribute->id])->first();
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
                            $productVariation->sale_price = $variation['sale_price']  ??0;

                            $productVariation->quantity = $variation['qty'];
                            $productVariation->weight = $variation['weight'];
                            $productVariation->variation_attributes_name_id = json_encode($variationAttributeIds);
                            $productVariation->sku = $variation['sku'];
                            $productVariation->save();
                            array_push($variationIds,$productVariation->id);
                        }
                    }
                    ChowhubProductVariation::where('product_id',$products->id)->whereNotIn('id', array_unique($variationIds))->delete();
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

        ChowhubProductVariation::where('product_id', $id)->delete();
        ChowhubProductGallery::where('product_id', $id)->delete();
        ChowhubVariationAttributeValue::where('product_id', $id)->delete();
        ChowhubProductTag::where('product_id', $id)->delete();
        ChowhubProductBackendTag::where('product_id', $id)->delete();

        ChowhubProduct::find($id)->delete();

        ChowhubProductFeaturePageImage::where('product_id', $id)->delete();
        ChowhubProductDescriptionImage::where('product_id', $id)->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

    public function save_photo(Request $request)
    {


        if ($request->file('images'))
        {
            $filename = $request->images->hashname();
            $image = Image::make($request->images)->resize(619, 577);
            Storage::disk('s3')->put('/images/products/'.$filename, $image->stream(), 'public');
            $path = Storage::disk('s3')->url('images/products/'.$filename);
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

    public function save_description_photo(Request $request)
    {


        if ($request->file('description_images'))
        {
            $filename = $request->description_images->hashname();
            $image = Image::make($request->description_images)->resize(1238, 652);
            Storage::disk('s3')->put('/images/products/'.$filename, $image->stream(), 'public');
            $path = Storage::disk('s3')->url('images/products/'.$filename);
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

        ChowhubProductGallery::find($request->id)->delete();

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }

    public function del_description_photo(Request $request)
    {

        ChowhubProductDescriptionImage::find($request->id)->delete();

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }

    public function del_feature_page_photo(Request $request)
    {

        ChowhubProductFeaturePageImage::find($request->id)->delete();

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }
    public function duplicate(Request $request)
    {

        $id=$request->id;
        $product = ChowhubProduct::find($id);
        $productGallery = ChowhubProductGallery::where('product_id',$id)->get();

        $variationAttributeValue = ChowhubVariationAttributeValue::where('product_id',$id)->get();
        $productTag = ChowhubProductTag::where('product_id',$id)->get();
        $productBrand = ChowhubProductBrand::where('product_id',$id)->get();

        $productBackendTag = ChowhubProductBackendTag::where('product_id',$id)->get();
        $chowhubProductDescriptionImage = ChowhubProductDescriptionImage::where('product_id',$id)->get();
        $chowhubProductFeaturePageImage = ChowhubProductFeaturePageImage::where('product_id',$id)->get();
        $productVariation = ChowhubProductVariation::where('product_id',$id)->get();

        $products = ChowhubProduct::create([
            'productName' =>   $product->productName . ' copy'.date("d-h-m-s"),
            'description' =>  $product->description,
            'sku' =>  $product->sku,
            'pet_type' =>  $product->pet_type,
            'age' =>  $product->age,
            'food_type' =>  $product->food_type,
            'protein_type' =>  $product->protein_type,
            'type' =>  $product->type,
            'store_id' =>  $product->store_id,
            'feature_image' =>  $product->feature_image,
            'real_price' =>  $product->real_price,
            'sale_price' =>  $product->sale_price,
            'weight' =>  $product->weight,
            'quantity' =>  $product->quantity,
            'status' =>  $product->status,
        ]);


     if(!empty($productGallery)){
        foreach ($productGallery as $key => $value) {

            ChowhubProductGallery::create([
                'product_id' =>  $products->id,
                'image_path' =>  $value->image_path,
                'priority' =>  $value->priority,

            ]);
        }
     }
    if(!empty($variationAttributeValue)){
        $variationAttributeIds = [];
        foreach ($variationAttributeValue as $key => $value) {

         $selectedAttrubutes=   ChowhubVariationAttributeValue::create([
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
    if(!empty($productTag)){
        foreach ($productTag as $key => $value) {

            ChowhubProductTag::create([
                'product_id' =>  $products->id,
                'tag_id' =>  $value->tag_id,
            ]);
        }
    }
      if(!empty($productBackendTag)){
        foreach ($productBackendTag as $key => $value) {

            ChowhubProductBackendTag::create([
                'product_id' =>  $products->id,
                'tag_id' =>  $value->tag_id,
            ]);
        }
    }
    if(!empty($productBrand)){
        foreach ($productBrand as $key => $value) {

            ChowhubProductBrand::create([
                'product_id' =>  $products->id,
                'brand_id' =>  $value->brand_id,
            ]);
        }
    }
    if(!empty($chowhubProductDescriptionImage)){
        foreach ($chowhubProductDescriptionImage as $key => $value) {

            ChowhubProductDescriptionImage::create([
                'product_id' =>  $products->id,
                'image_path' =>  $value->image_path,
                'priority' =>  $value->priority,
            ]);
        }
     }

     if(!empty($chowhubProductFeaturePageImage)){
        foreach ($chowhubProductFeaturePageImage as $key => $value) {

            ChowhubProductFeaturePageImage::create([
                'product_id' =>  $products->id,
                'image_path' =>  $value->image_path,
                'priority' =>  $value->priority,
            ]);
        }
     }
      if(!empty($productVariation)){
            foreach ($productVariation as $key => $value) {

                ChowhubProductVariation::create([
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
    return redirect('admin/chowhub-products')->with('success', 'Product Duplicate successfully!');
    }

}
