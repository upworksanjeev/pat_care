<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use DataTables;
use App\Http\Requests\Admin\Pet\PetRequest;
use Storage;
class PetController extends Controller
{


    public function index($id)
    {

    }


    public function store(PetRequest $request)
    {

            $user = auth('api')->user();

            if (!$user)
            {
                $roleGuest = Role::where(['name' => 'Guest'])->first();
                $user = User::updateOrCreate(
                                [
                                    'email' => $request->email,
                                ],
                                [
                                    'name' => $request->name,
                                    'password' => bcrypt(uniqid(rand(), true))
                ]);
                $user->assignRole($roleGuest);
            }


                $inputs['user_id']=$user->id;
                $inputs['name']=$request->name;
                $inputs['type']=$request->type;
                $inputs['age']=$request->age;
                if ($request->hasFile('image'))
                {
                    $path = Storage::disk('s3')->put('images/pet', $request->image);
                    $path = Storage::disk('s3')->url($path);
                    $inputs['image'] = $path;
                }
                Pet::create($inputs);

            return response()->json([
                'success' => true,'message' => 'Pet created successfull'
            ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id , Request $request)
    {

            $data = Pet::with('user')->where('user_id',$id)->get();
            if ($request->ajax())
            {
            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function ($row)
                            {
                                $action = '<span class="action-buttons">

                        <a  href="' . route("edit", $row) . '" class="btn btn-sm btn-info btn-b"><i class="las la-pen"></i>
                        </a>

                        <a href="' . route("destroy", $row) . '"
                                class="btn btn-sm btn-danger remove_us"
                                title="Delete User"
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="Please Confirm"
                                data-confirm-text="Are you sure that you want to delete this pet?"
                                data-confirm-delete="Yes, delete it!">
                                <i class="las la-trash"></i>
                            </a>
                    ';
                                return $action;
                            })
                            ->rawColumns(['action'])
                            ->make(true);

                        }
        return view('admin.pet.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pet=Pet::find($id);
        $pets = Pet::where('id', '!=', $pet->id)->get();
        return view('admin.pet.addEdit', compact('pet', 'pets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PetRequest $request, $id)
    {
        $pet=Pet::find($id);
        $inputs = $request->all();
        if ($request->hasFile('image'))
        {
            $path = Storage::disk('s3')->put('images', $request->image);
            $path = Storage::disk('s3')->url($path);
            $inputs['image'] = $path;
        }
        $pet->update($inputs);
        return back()->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
   
       Pet::find($id)->delete();
       return back()->with('success', 'Pet Deleted successfully!');
    }
}
