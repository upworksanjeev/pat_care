<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\LightSpeed;
use Carbon\Carbon;
use DataTables;
use App\Http\Requests\Admin\Brands\AddBrands;
use App\Http\Requests\Admin\Brands\UpdateBrands;
use Storage;
use App\Traits\AccessTokenTrait;
use App\Traits\BrandTrait;
class BrandsController extends Controller
{
    use AccessTokenTrait , BrandTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {
            $data = Brand::orderby('id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {
                                $action = '<span class="action-buttons">
                                    <a  href="' . route("brands.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("brands.destroy", $row) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this Brand?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->addColumn('productCount', function ($row)
                            {
                                $productCount=Product::where('brand_id',$row->id)->count();
                                return $productCount;
                            })
                            ->rawColumns(['action','productCount'])
                            ->make(true)
            ;
        }

        return view('admin.brands.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        return view('admin.brands.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBrands $request)
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
        $brandId= $this->storeBrand($inputs);
       
        if ($request->hasFile('logo'))
        {
            $path = Storage::disk('s3')->put('images', $request->logo);
            $path = Storage::disk('s3')->url($path);
            $inputs['logo'] = $path;
        }
        if ($request->hasFile('cover_image'))
        {
            $path = Storage::disk('s3')->put('images', $request->cover_image);
            $path = Storage::disk('s3')->url($path);
            $inputs['cover_image'] = $path;
        }
        $inputs['lightspeed_brand_id'] = $brandId;
        Brand::create($inputs);

        return back()->with('success', 'Brand addded successfully!');
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
    public function edit(Brand $brand)
    {
        $brands = Brand::where('id', '!=', $brand->id)->get();
        return view('admin.brands.addEdit', compact('brand', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrands $request, Brand $brand)
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
        $this->updateBrand($inputs, $brand->lightspeed_brand_id);
        if ($request->hasFile('logo'))
        {
            $path = Storage::disk('s3')->put('images', $request->logo);
            $path = Storage::disk('s3')->url($path);
            $inputs['logo'] = $path;
        }
        if ($request->hasFile('cover_image'))
        {
            $path = Storage::disk('s3')->put('images', $request->cover_image);
            $path = Storage::disk('s3')->url($path);
            $inputs['cover_image'] = $path;
        }
        $brand->update($inputs);
        return back()->with('success', 'Brand updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        
        // $lightspeed=LightSpeed::orderBy('id', 'asc')->first();
        // $currentTime=Carbon::now();
        // if(empty($lightspeed->access_token)){
        //      $this->accessToken();
        // }
        //     if(empty($lightspeed->account_id)){
        //         $this->fetchAccount();
        // }
        // if($lightspeed->expired_at < $currentTime){
        // $this->refreshToken();
        // }
        // $this->deleteBrand($brand->lightspeed_brand_id);
        $brand->delete();
        return back()->with('success', 'Brand deleted successfully!');
    }

}
