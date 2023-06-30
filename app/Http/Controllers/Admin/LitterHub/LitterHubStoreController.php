<?php

namespace App\Http\Controllers\Admin\LitterHub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Litterhub\LitterhubStore;
use DataTables;
use App\Http\Requests\Admin\Litterhub\Stores\AddStores;
use App\Http\Requests\Admin\Litterhub\Stores\UpdateStores;
use Storage;

class LitterHubStoreController extends Controller
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
            $data = LitterhubStore::orderby('id','DESC');

            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {

                                $action = '<span class="action-buttons">
                                    <a  href="' . route("litterhub-store.edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                                    </a>

                                    <a href="' . route("litterhub-store.destroy", $row) . '"
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
                            ->rawColumns(['action'])
                            ->make(true)
            ;
        }

        return view('admin.litterhub.stores.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.litterhub.stores.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddStores $request)
    {


        $Store = new LitterhubStore;
        $Store->name = $request->name;
        $Store->save();

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
        $store = LitterhubStore::find($id);

        $stores = LitterhubStore::where('id', '!=', $id)->get();
        return view('admin.litterhub.stores.addEdit', compact('stores', 'store'));
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

        $Store = LitterhubStore::find($id);
        $Store->name = $request->name;
        $Store->save();

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
        LitterhubStore::find($id)->delete();

        return back()->with('success', 'Store deleted successfully!');
    }

}
