<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Admin\Profile\UpdateUserProfile;
use Storage;
class AdminController extends Controller
{
    /**
     * View Profile
     * @return type
     */
    public function viewProfile(){
        return view('admin.profile.viewProfile');
    }
    
    /**
     * Update Profile view
     * @return type
     */
    public function updateProfile(){
          return view('admin.profile.editprofile');
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserProfile(UpdateUserProfile $request , $id ){

        
    $user = User::find($id);
    if(!empty($request->password)){
        $user->password =  bcrypt($request->password);
    }
    if($request->hasFile('profile')){
        $path = Storage::disk('s3')->put('images', $request->profile);
        $path = Storage::disk('s3')->url($path);
        $user->profile = $path; 
    }
    $user->name = $request->name;
    $user->save();
    return back()->with('success','User updated successfully!');
       
  }
}
