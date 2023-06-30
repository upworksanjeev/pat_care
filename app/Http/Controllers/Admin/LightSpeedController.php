<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

use App\Http\Requests\Admin\Lightspeed\AddLightSpeed;


class LightSpeedController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        return view('admin.lightspeed.addEdit');
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
    public function store(AddLightSpeed $request)
    {
      
        $client_id=$request->client_id;
        $role=$request->role;

      $url= "https://cloud.lightspeedapp.com/oauth/authorize.php?response_type=code&client_id=".$client_id."&scope=".$role;
      return redirect()->away($url);
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
