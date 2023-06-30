<?php

namespace App\Http\Controllers\Admin\LitterHub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\User;

use App\Models\Category;
use App\Models\Litterhub\LitterhubProduct;
use DataTables;
use App\Http\Requests\Admin\Coupon\AddCoupon;
use App\Http\Requests\Admin\Coupon\UpdateCoupon;
use Storage;

class LitterHubCouponController extends Controller
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
            $data = Coupon::where('product_type', '=' ,'litterhub')->orderby('id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {

                                $action = '<span class="action-buttons">
                                    <a  href="' . route("litterhub-coupons.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("litterhub-coupons.destroy", $row) . '"
                                            class="btn btn-sm btn-danger remove_us"
                                            title="Delete User"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-method="DELETE"
                                            data-confirm-title="Please Confirm"
                                            data-confirm-text="Are you sure that you want to delete this coupons?"
                                            data-confirm-delete="Yes, delete it!">
                                            <i class="las la-trash"></i>
                                        </a>
                                ';
                                return $action;
                            })
                            ->rawColumns(['action'])
                            ->make(true)
            ;
        }

        return view('admin.litterhub.coupons.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $categories = Category::select('name', 'id')->where(['parent' => 0, 'type' => 'Chowhub'])->get();
        $products = LitterhubProduct::select('productName', 'id')->get();

        return view('admin.litterhub.coupons.addEdit', compact('categories','products'));
    }

    /**
     * coupons a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCoupon $request)
    {
        $inputs = $request->all();



        $coupon= Coupon::create(['name' => $inputs['name'],
        'code' => $inputs['code']??null,
        'type' => $inputs['type'] ??null,
        'value' => $inputs['value'] ??null,
        'count' => $inputs['count'] ??null,
        'started_at' => $inputs['started_at'] ??null,
        'expired_at' => $inputs['expired_at'] ??null,
        'lifetime_coupon' => $inputs['lifetime_coupon'] ??0,
        'apply_to' => $inputs['apply_to'] ??null,
        'category_id' =>  (isset($inputs['category_id'])) ? json_encode($inputs['category_id']) : null,

        'product_id' => (isset($inputs['product_id'])) ? json_encode($inputs['product_id']) : null,
        'product_type' => 'litterhub' ]);

         return back()->with('success', 'Coupon addded successfully!');



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

        $coupon = Coupon::find($id);
        $categories = Category::with('childrens')->where(['parent' => 0, 'type' => 'Chowhub'])->get();

        $coupons = Coupon::where('id', '!=', $id)->get();
       $products = LitterhubProduct::select('productName', 'id')->get();

        return view('admin.litterhub.coupons.addEdit', compact('coupons', 'coupon', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCoupon $request, $id)
    {

        $inputs = $request->all();

        Coupon::find($id)->update(['name' => $inputs['name'],
        'code' => $inputs['code']??null,
        'type' => $inputs['type'] ??null,
        'value' => $inputs['value'] ??null,
        'count' => $inputs['count'] ??null,
        'started_at' => $inputs['started_at'] ??null,
        'expired_at' => $inputs['expired_at'] ??null,
        'lifetime_coupon' => $inputs['lifetime_coupon'] ??0,
        'apply_to' => $inputs['apply_to'] ??null,
        'category_id' => (isset($inputs['category_id'])) ? json_encode($inputs['category_id']) : null,

        'product_id' => (isset($inputs['product_id'])) ? json_encode($inputs['product_id']) : null,
        'product_type' => 'litterhub' ]);
         return back()->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Coupon::find($id)->delete();

        return back()->with('success', 'Coupon deleted successfully!');
    }



}
