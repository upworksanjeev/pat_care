<?php

namespace App\Http\Controllers\IotAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use App\Http\Requests\IotAdmin\UserRequest;
use App\Http\Requests\IotAdmin\UpdateUserRequest;

class UserListController extends Controller
{

  //below function show all data in table
      public function index(Request $request){
       if ($request->ajax()) {
          $data = User::with('roles')->whereHas(
            'roles', function($q){
                $q->where('name','!=','Admin');
            })
        ->get();
          return Datatables::of($data)
                  ->addIndexColumn()
                  ->addColumn('action', function($row){
                    $btn = "<a href="."/iot-admin/edit/$row->id". " class='btn btn-sm btn-info btn-b'><i class='las la-pen'></i></a>";
                    $btn .= "<a href='#' onclick= "."showModalFunction($row->id)"." class='btn btn-sm btn-danger'><i class='las la-trash'></i></a>";
                          return $btn;
                  })
                  ->rawColumns(['action'])
                  ->make(true);
      }      
      return view('iotAdmin.users.userlist');
      }


//below function open the edit tab
      public function editUser($id){
        $user= User::with('roles')->where('id',$id)->first();
        $role = Role::where('name','!=','Admin')->get();
        return view('iotAdmin.users.editUser',compact('user','role'));
      }


//below function update the user data
      public function updateUser(UpdateUserRequest $request){       
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
         $role = Role::updateOrCreate(['name' => $request->role]);
        $user= User::findorfail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $user->syncRoles([$role]);       
        return redirect('iot-admin/user')->with('success', 'Updated');
      }


//below function delete the user data
      public function delUser($id){
        $user = User::findorfail($id)->delete();
        return Redirect::back()->with('success', 'Deleted');
      }


// below function open the add user page
      public function addUser(){
        $role = Role::where('name','!=','Admin')->get();
        return view('iotAdmin.users.addUser',compact('role'));
      }

// below function store user data in db
      public function addNewUser(UserRequest $request){
         $user = User::updateOrCreate([
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($request->password),
        ]);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::updateOrCreate(['name' => $request->role]);
        $user->assignRole($role);
       return Redirect::back()->with('success', 'Created');
   }

}
