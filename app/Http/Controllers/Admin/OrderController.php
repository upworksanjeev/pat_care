<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Orders\UpdateOrders;
use App\Models\Order;
use App\Models\Shipping;
use DataTables;

class OrderController extends Controller
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
            $data = Order::with('user')->orderby('id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {
                                $action = '

                                <span class="action-buttons">
                                <a  href="' . route("orders.show", $row) . '" class="btn btn-sm btn-info btn-b"><i class="fa fa-eye" aria-hidden="true"></i>

                                </a>

                                <a href="' . route("orders.destroy", $row) . '"
                                        class="btn btn-sm btn-danger remove_us"
                                        title="Delete User"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        data-method="DELETE"
                                        data-confirm-title="Please Confirm"
                                        data-confirm-text="Are you sure that you want to delete this Order?"
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

        return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('user', 'shipping', 'orderItems', 'orderItems.products')->where('id',$id)->first();

        return view('admin.orders.view_single_order', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.,
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with('user', 'shipping', 'orderItems', 'orderItems.products')->where('id',$id)->first();

        $orders = Order::where('id', '!=', $order->id)->get();
        return view('admin.orders.addEdit', compact('order', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrders $request, Order $order)
    {


        $shipping = Shipping::find($request->id)->update(
                [
                    'sh_name' => $request->sh_name,
                    'sh_city' => $request->sh_city,
                    'sh_state' => $request->sh_state,
                    'sh_address' => $request->sh_address,
                    'sh_country' => $request->sh_country,
                    'sh_zip_code' => $request->sh_zip_code,
                    'sh_phone' => $request->sh_phone,
                    'sh_email' => $request->sh_email,
                ]
        );

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted successfully!');
    }

}
