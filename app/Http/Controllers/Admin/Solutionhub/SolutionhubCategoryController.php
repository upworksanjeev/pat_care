<?php

namespace App\Http\Controllers\Admin\Solutionhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use DataTables;
use App\Http\Requests\Admin\Solutionhub\Category\AddCategory;
use App\Http\Requests\Admin\Solutionhub\Category\UpdateCategory;
use Illuminate\Support\Str;
use Storage;

class SolutionhubCategoryController extends Controller
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
            $data = Category::where('type', 'Solutionhub')->get();
           

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
                            ->addColumn('subcategroy', function ($row)
                            {
                               
                                $cat= Category::where('parent',$row->id)->get();
                                $subcategroy=[];
                            foreach ($cat as $key => $value) {
                              
                         
                                array_push($subcategroy,$value->name);
                            }
                           
                            $subcategroy = implode(',',$subcategroy);
                            
                                return $subcategroy;
                            })
                            ->addColumn('action', function ($row)
                            {
                                $action = '<span class="action-buttons">
                                    <a  href="' . route("solutionhub-categories.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("solutionhub-categories.destroy", $row) . '"
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
                            ->rawColumns(['action', 'status','subcategroy'])
                            ->make(true)
            ;
        }

        return view('admin.solutionhub.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('type', 'Solutionhub')->get();
        return view('admin.solutionhub.categories.addEdit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCategory $request)
    {
        $slug = Str::slug($request->name);
        $inputs = $request->all();
        if ($request->hasFile('feature_image'))
        {
            $path = Storage::disk('s3')->put('images/categories', $request->feature_image);
            $path = Storage::disk('s3')->url($path);
            $inputs['feature_image'] = $path;
        }

        $inputs['slug'] = $slug;

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
    public function edit($id)
    {
        $category = Category::where('id', '=', $id)->where('type', 'Solutionhub')->first();
        $categories = Category::where('id', '!=', $id)->where('type', 'Solutionhub')->get();
        return view('admin.solutionhub.categories.addEdit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategory $request, $id)
    {
        $category = Category::find($id);
        $slug = Str::slug($request->name);
        $inputs = $request->all();
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
    public function destroy($id)
    {

        Category::find($id)->delete();
        return back()->with('success', 'Category deleted successfully!');
    }

}
