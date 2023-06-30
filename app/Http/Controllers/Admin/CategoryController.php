<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\LightSpeed;
use Carbon\Carbon;
use DataTables;
use App\Http\Requests\Admin\Category\AddCategory;
use App\Http\Requests\Admin\Category\UpdateCategory;
use Illuminate\Support\Str;
use Storage;
use Intervention\Image\Facades\Image;
use App\Traits\AccessTokenTrait;
use App\Traits\CategoryTrait;

class CategoryController extends Controller
{
    use AccessTokenTrait , CategoryTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $data = Category::where('type', 'Product')->orderby('id','DESC');

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
                            ->addColumn('productCount', function ($row)
                            {
                                $productCount=Product::where('category_id',$row->id)->count();
                                return $productCount;
                            })
                            ->addColumn('action', function ($row)
                            {
                                $action = '<span class="action-buttons">
                                    <a  href="' . route("categories.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("categories.destroy", $row) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this Category?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->rawColumns(['action', 'status','productCount'])
                            ->make(true)
            ;
        }

        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('type', 'Product')->get();
        return view('admin.categories.addEdit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCategory $request)
    {   
      
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

       
         $inputs = $request->all();
  
         $categoryId= $this->storeCategory($inputs);

         $slug = Str::slug($request->name);
       
        if ($request->hasFile('feature_image'))
        {
            $path = Storage::disk('s3')->put('images/categories', $request->feature_image);
            $path = Storage::disk('s3')->url($path);
            $inputs['feature_image'] = $path;
        }

        $inputs['slug'] = $slug;
        $inputs['lightspeed_category_id'] = $categoryId ?? null;


        Category::create($inputs);

        return back()->with('success', 'Category addded successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->where('type', 'Product')->get();
        return view('admin.categories.addEdit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategory $request, Category $category)
    {
        $slug = Str::slug($request->name);
        $inputs = $request->all();
    
      if($category->lightspeed_category_id){
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
          $categoryId= $this->updateCategory($inputs,$category->lightspeed_category_id);
      }
        if ($request->hasFile('feature_image'))
        {
            $path = Storage::disk('s3')->put('images/categories', $request->feature_image);
            $path = Storage::disk('s3')->url($path);
            $inputs['feature_image'] = $path;
        }
        $inputs['slug'] = $slug;
        $category->update($inputs);

        return back()->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->lightspeed_category_id){
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
              $categoryId= $this->deleteCategory($category->lightspeed_category_id);
          }
     
      
        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

}
