<?php

namespace App\Http\Controllers\Admin\Solutionhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solutionhub\SolutionhubProduct;
use App\Models\Category;
use App\Models\Solutionhub\SolutionhubProductCategory;
use App\Models\Solutionhub\SolutionHubCategory;
use App\Models\Solutionhub\SolutionHubProblem;
use App\Models\Solutionhub\SolutionHubProblemSolution;
use App\Models\Solutionhub\SolutionHubProductSolution;
use App\Models\Solutionhub\SolutionHubProductProblem;
use App\Models\Solutionhub\SolutionhubTag;
use App\Models\Solutionhub\SolutionhubProductTag;
use App\Models\Solutionhub\SolutionhubBackendTag;
use App\Models\Solutionhub\SolutionhubProductBackendTag;
use App\Models\Solutionhub\SolutionhubBrand;
use App\Models\Solutionhub\SolutionhubProductBrand;
use DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Solutionhub\Product\AddProduct;
use App\Http\Requests\Admin\Solutionhub\Product\UpdateProduct;
use Storage;
use Intervention\Image\Facades\Image;

class SolutionhubProductController extends Controller
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
            $data = SolutionhubProduct::with('backendtags','tags','brands')->get();

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
                                <a  href="' . url("admin/solutionhub-product/duplicate?id=".$row->id) . '" class="btn btn-sm btn-info btn-b"><i class="las la-copy" title="Duplicate Product"></i>
                                </a>
                                    <a  href="' . route("solutionhub-products.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("solutionhub-products.destroy", $row) . '"
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

        return view('admin.solutionhub.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = SolutionHubCategory::get();

        return view('admin.solutionhub.products.addEdit',compact('categories'));
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

        if (!empty($inputs['feature_image']))
        {
            $path = Storage::disk('s3')->put('images', $inputs['feature_image']);
            $image_path = Storage::disk('s3')->url($path);
            $inputs['feature_image'] = $image_path;
        }
        $products=SolutionhubProduct::create($inputs);
        $tags = explode(",", $inputs['tag']);
        $backendtags = explode(",", $inputs['backend_tag']);
        $brands = explode(",", $inputs['brand']);
        if (!empty($inputs['problem_id']))
        {
            foreach ($inputs['problem_id']  as $problem)
            {

                $problemSave = new SolutionHubProductProblem;
                $problemSave->category_id = $inputs['category_id'];
                $problemSave->product_id = $products->id;
                $problemSave->problem_id = $problem;
                $problemSave->save();
            }
        }
        if (!empty($inputs['solution_id']))
        {
            foreach ($inputs['solution_id']  as $solution)
            {

                $solutionSave = new SolutionHubProductSolution;
                $solutionSave->category_id = $inputs['category_id'];
                $solutionSave->product_id = $products->id;
                $solutionSave->solution_id = $solution;
                $solutionSave->save();
            }
        }

        if (!empty($tags))
        {
            foreach ($tags as $vakey => $tagName)
            {

                $tag = SolutionhubTag::updateOrCreate([
                            'name' => $tagName
                                ], [
                            'name' => $tagName
                ]);

                $tagValue = new SolutionhubProductTag;
                $tagValue->tag_id = $tag->id;
                $tagValue->product_id = $products->id;
                $tagValue->save();
            }
        }
        if (!empty($brands))
        {
            foreach ($brands as $vakey => $brandName)
            {

                $brand = SolutionhubBrand::updateOrCreate([
                            'name' => $brandName
                                ], [
                            'name' => $brandName
                ]);

                $brandValue = new SolutionhubProductBrand;
                $brandValue->brand_id = $brand->id;
                $brandValue->product_id = $products->id;
                $brandValue->save();
            }
        }
        if (!empty($backendtags))
        {
            foreach ($backendtags as $vakey => $tagName)
            {

                $tag = SolutionhubBackendTag::updateOrCreate([
                            'name' => $tagName
                                ], [
                            'name' => $tagName
                ]);

                $tagValue = new SolutionhubProductBackendTag;
                $tagValue->tag_id = $tag->id;
                $tagValue->product_id = $products->id;
                $tagValue->save();
            }
        }

        return redirect('admin/solutionhub-products')->with('success', 'Product added successfully!');
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
        $categories = SolutionHubCategory::get();
      
        $product = SolutionhubProduct::with('solutionCategory','problemCategory')->where('id', $id)->first();
      
        $solutioncategory=[];
        $problemcategory=[];

        if($product->solutionCategory){
            foreach ($product->solutionCategory as $key => $value) {
            array_push($solutioncategory, $value->solution_id);
            }
        }
        if($product->problemCategory){
            foreach ($product->problemCategory as $key => $value) {
            array_push($problemcategory, $value->problem_id);
            }
        }
        $all_problem= SolutionHubProblem::where('solution_category_id', $product->category_id)->get();
       $all_solution= SolutionHubProblemSolution::where('solution_category_id', $product->category_id)->get();
 
   
        return view('admin.solutionhub.products.addEdit', compact('solutioncategory','product','problemcategory','all_problem','all_solution','categories'));
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
      
        if (!empty($inputs['feature_image']))
        {
            $path = Storage::disk('s3')->put('images', $inputs['feature_image']);
            $image_path = Storage::disk('s3')->url($path);
            $inputs['feature_image'] = $image_path;
        }

        SolutionhubProduct::find($id)->update($inputs);
        $tags = explode(",", $inputs['tag']);
        $backendtags = explode(",", $inputs['backend_tag']);
        $brands = explode(",", $inputs['brand']);
        if (!empty($inputs['problem_id']))
        {
            SolutionHubProductProblem::where('product_id', $id)->delete();
            foreach ($inputs['problem_id']  as $problem)
            {

                $problemSave = new SolutionHubProductProblem;
                $problemSave->category_id = $inputs['category_id'];
                $problemSave->product_id = $id;
                $problemSave->problem_id = $problem;
                $problemSave->save();
            }
        }
        if (!empty($inputs['solution_id']))
        {
            SolutionHubProductSolution::where('product_id', $id)->delete();
            foreach ($inputs['solution_id']  as $solution)
            {

                $solutionSave = new SolutionHubProductSolution;
                $solutionSave->category_id = $inputs['category_id'];
                $solutionSave->product_id = $id;
                $solutionSave->solution_id = $solution;
                $solutionSave->save();
            }
        }
   

        if (!empty($tags))
        {
            SolutionhubProductTag::where('product_id', $id)->delete();
            foreach ($tags as $vakey => $tagName)
            {

                $tag = SolutionhubTag::updateOrCreate([
                            'name' => $tagName
                                ], [
                            'name' => $tagName
                ]);

                $tagValue = new SolutionhubProductTag;
                $tagValue->tag_id = $tag->id;
                $tagValue->product_id = $id;
                $tagValue->save();
            }
        }
        if (!empty($backendtags))
        {
            SolutionhubProductBackendTag::where('product_id', $id)->delete();
            foreach ($backendtags as $vakey => $tagName)
            {

                $tag = SolutionhubBackendTag::updateOrCreate([
                            'name' => $tagName
                                ], [
                            'name' => $tagName
                ]);

                $tagValue = new SolutionhubProductBackendTag;
                $tagValue->tag_id = $tag->id;
                $tagValue->product_id = $id;
                $tagValue->save();
            }
        }
        if (!empty($brands))
        {
            SolutionhubProductBrand::where('product_id', $id)->delete();
            foreach ($brands as $vakey => $brandName)
            {

                $brand = SolutionhubBrand::updateOrCreate([
                            'name' => $brandName
                                ], [
                            'name' => $brandName
                ]);

                $brandValue = new SolutionhubProductBrand;
                $brandValue->brand_id = $brand->id;
                $brandValue->product_id = $id;
                $brandValue->save();
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
        SolutionhubProductTag::where('product_id', $id)->delete();
        SolutionhubProductBackendTag::where('product_id', $id)->delete();
        SolutionhubProduct::find($id)->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

    public function duplicate(Request $request)
    {

        $id=$request->id;
        $product = SolutionhubProduct::find($id);
        $productTag = SolutionhubProductTag::where('product_id',$id)->get();
        $productBackendTag = SolutionhubProductBackendTag::where('product_id',$id)->get();
        $productBrand = SolutionhubProductBrand::where('product_id',$id)->get();
        $productCategory = SolutionhubProductCategory::where('product_id',$id)->get();

          $products = SolutionhubProduct::create([
            'productName' =>   $product->productName . ' copy'.date("d-h-m-s"),
            'description' =>  $product->description,
            'tag' =>  $product->tag,
            'status' =>  $product->status,
            'feature_image' =>  $product->feature_image,
        ]);
    if(!empty($productTag)){
        foreach ($productTag as $key => $value) {

            SolutionhubProductTag::create([
                'product_id' =>  $products->id,
                'tag_id' =>  $value->tag_id,
            ]);
        }
    }
    if(!empty($productCategory)){
        foreach ($productCategory as $key => $value) {

            SolutionhubProductCategory::create([
                'product_id' =>  $products->id,
                'category_id' =>  $value->category_id,
            ]);
        }
    }
      if(!empty($productBackendTag)){
        foreach ($productBackendTag as $key => $value) {

            SolutionhubProductBackendTag::create([
                'product_id' =>  $products->id,
                'tag_id' =>  $value->tag_id,
            ]);
        }
    }
    if(!empty($productBrand)){
        foreach ($productBrand as $key => $value) {

            SolutionhubProductBrand::create([
                'product_id' =>  $products->id,
                'brand_id' =>  $value->brand_id,
            ]);
        }
    }
    return redirect('admin/solutionhub-products')->with('success', 'Product Duplicate successfully!');

    }
    public function problem(Request $request){
     
       $data['problem']= SolutionHubProblem::where('solution_category_id', $request->id)->get();
       $data['solution']= SolutionHubProblemSolution::where('solution_category_id', $request->id)->get();

       return response()->json($data);
    }
}
