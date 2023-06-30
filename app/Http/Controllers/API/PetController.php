<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\API\PetRequest;
use App\Http\Resources\Pet\PetResource;
use Storage;
class PetController extends Controller
{
    /**
     * @OA\Get(
     *      path="/pet",
     *      operationId="pet",
     *      tags={"Pet"},
     *  *      security={
     *          {"Bearer": {}},
     *          },
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/PetResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {

        $user=auth('api')->user();

        if(!empty($user)){
            $pets = Pet::where(['user_id'=>$user->id])->orderBy('id', 'DESC')->get();

            return  PetResource::collection($pets);
        }else{
            return response()->json(['success' => false, 'message' => "No User Found"]);
        }

    }

   /**
     * @OA\Post(
     *      path="/pet/create",
     *      operationId="pet store",
     *      tags={"Pet"},
     *
     *     summary="pet store",
     *  *      *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PetRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pages",
     *         @OA\JsonContent(ref="#/components/schemas/PetResponse")
     *     ),
     *    @OA\Response(
     *      response=400,ref="#/components/schemas/BadRequest"
     *    ),
     *    @OA\Response(
     *      response=404,ref="#/components/schemas/Notfound"
     *    ),
     *    @OA\Response(
     *      response=500,ref="#/components/schemas/Forbidden"
     *    )
     * )
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExampleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
