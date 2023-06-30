<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use DataTables;
use App\Http\Requests\Admin\Setting\Addsetting;
use App\Http\Requests\Admin\Setting\UpdateSetting;
use Storage;

class SettingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $setting = Setting::orderBy('id', 'asc')->first();
        return view('admin.settings.addEdit', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.settings.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Addsetting $request)
    {

        $inputs = $request->all();
        if ($request->hasFile('logo'))
        {
            $path = Storage::disk('s3')->put('images', $request->logo);
            $path = Storage::disk('s3')->url($path);
            $inputs['logo'] = $path;
        }
        Setting::create($inputs);

        return back()->with('success', 'Setting addded successfully!');
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
        $setting = Setting::find(1);
        return view('admin.settings.addEdit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSetting $request, Setting $Setting)
    {

        $inputs = $request->all();
        if ($request->hasFile('logo'))
        {
            $path = Storage::disk('s3')->put('images', $request->logo);
            $path = Storage::disk('s3')->url($path);
            $inputs['logo'] = $path;
        }
        $Setting->update($inputs);
        return back()->with('success', 'Setting updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $Setting)
    {
        $Setting->delete();
        return back()->with('success', 'Setting deleted successfully!');
    }

}
