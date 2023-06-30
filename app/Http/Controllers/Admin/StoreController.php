<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\StoreGallery;
use App\Models\LightSpeed;
use DataTables;
use App\Http\Requests\Admin\Stores\AddStores;
use App\Http\Requests\Admin\Stores\UpdateStores;
use Storage;
use App\Traits\AccessTokenTrait;
use App\Traits\VendorTrait;
class StoreController extends Controller
{
    use AccessTokenTrait , VendorTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {
            $data = Store::orderby('id','DESC');

            return DataTables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {

                                $action = '<span class="action-buttons">
                                    <a  href="' . route("stores.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("stores.destroy", $row) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this stores?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->addColumn('productCount', function ($row)
                            {
                                $productCount=Product::where('store_id',$row->id)->count();
                                return $productCount;
                            })
                            ->rawColumns(['action','productCount'])
                            ->make(true)
            ;
        }

        return view('admin.stores.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.stores.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddStores $request)
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
        $vendorId= $this->storeVendor($inputs);

            $store = Store::create([
                'name' =>  $inputs['name'],
                'description' =>  $inputs['description'],
                'lightspeed_vendor_id' => $vendorId,
                'address' =>  $inputs['address'],
                'city' =>  $inputs['city'],
                'state' =>  $inputs['state'],
                'country' =>  $inputs['country'],
                'zip_code' =>  $inputs['zip_code'],
                'url' =>  $inputs['url'],
                'direction_link' =>  $inputs['direction_link'],
            
            ]);
            if(!empty($request->image)){
                foreach ($request->image as $key => $value) {
                    StoreGallery::create([
                        'store_id' =>  $store->id,
                        'image_path' =>  $value,
                        
                    ]);
                    
                }
            }
       
       

        return back()->with('success', 'Store addded successfully!');
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
        $store = Store::find($id);

        $stores = Store::where('id', '!=', $id)->get();
        return view('admin.stores.addEdit', compact('stores', 'store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStores $request, $id)
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
        
         Store::find($id)->update([
            'name' =>  $inputs['name'],
            'description' =>  $inputs['description'],
            'address' =>  $inputs['address'],
            'city' =>  $inputs['city'],
            'state' =>  $inputs['state'],
            'country' =>  $inputs['country'],
            'zip_code' =>  $inputs['zip_code'],
            'url' =>  $inputs['url'],
            'direction_link' =>  $inputs['direction_link'],
        
        ]);
        $store =Store::find($id);
        $this->updateVendor($inputs,$store->lightspeed_vendor_id);
        if(!empty($request->image)){
            foreach ($request->image as $key => $value) {
                StoreGallery::create([
                    'store_id' =>  $id,
                    'image_path' =>  $value,
                    
                ]);
                
            }
        }

        return back()->with('success', 'Store updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store= Store::find($id);
        if($store->lightspeed_vendor_id){
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
              $this->deleteVendor($store->lightspeed_vendor_id);
          }

        StoreGallery::where('store_id', $id)->delete();
        $store->delete();

        return back()->with('success', 'Store deleted successfully!');
    }
    public function storeimageAjax(Request $request)
    {
        if ($request->file('images'))
        {
            $path = Storage::disk('s3')->put('images/store', $request->images);
            $path = Storage::disk('s3')->url($path);
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

        StoreGallery::find($request->id)->delete();

        return Response()->json([
                    "success" => 'Deleted Successfully',
        ]);
    }
}
