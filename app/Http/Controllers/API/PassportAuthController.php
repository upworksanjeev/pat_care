<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use App\Http\Requests\API\RegisterUserRequest;
use App\Http\Requests\API\LoginUserRequest;
use App\Http\Requests\API\TokenRequest;
use App\Models\Setting;
use Illuminate\Support\Str;
use App\Http\Resources\Users\TokenResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PassportAuthController extends AppBaseController
{
    /**
     * Registration
     */
    /**
     * @OA\Post(
     * * path="/register",
     *   tags={"Users"},
     *   summary="Register new user",
     *   operationId="register",
     *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResgisterRequest")
     *     ),
     *    @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\JsonContent(ref="#/components/schemas/ResgisterResponse")
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
     * */

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
        ]);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::updateOrCreate(['name' => 'Customer']);
        $user->assignRole($role);
        $token = $user->createToken('LaravelAuthApp')->accessToken;

        $user->token = $token;
        return new TokenResource($user);
    }

    /**
     * Login
     */

    /**
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Users"},
     *     summary="Login existing user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponse")
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
    public function login(LoginUserRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data))
        {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            $user = auth()->user();
            $user->token = $token;
            return new TokenResource($user);
        } else
        {
            $data = User::Where('email', $request->email)->first();
            if (!$data)
            {
                return response()->json(['success' => false, 'message' => "User Doesn't Exists. Please Sign Up"], 400);
            } else
            {
                return response()->json(['success' => false, 'message' => "Password is incorrect. Try Again!"], 400);
            }
        }
    }

    public function logout(Request $request)
    {

        Auth::user()->token()->revoke();

        return response()->json([
                    'success' => false, 'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Login
     */

    /**
     * @OA\Post(
     *     path="/oauth/token",
     *     operationId="token",
     *     tags={"Users"},
     *     summary="Token existing user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TokenRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\JsonContent(ref="#/components/schemas/TokenResponse")
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
    public function oauth_token(TokenRequest $request)
    {


        $client_secret = env('API_ACCESS_CLIENT_SECRET')??'8BSSg7qMYw2NAJaiMhQOCYxGlFSs141SLfPRLU';
        $client_id = env('API_ACCESS_CLIENT_ID') ?? 2;

        if ($request['client_id'] != $client_id)
        {
            return response()->json(['success' => false, 'message' => "Invalid client Id"], 400);
        }

        if ($request['client_secret'] != $client_secret)
        {
            return response()->json(['success' => false, 'message' => "Invalid client secret"], 400);
        }


        $setting = Setting::orderBy('id', 'asc')->first();
        if(!empty($setting)){
            $updated_at = $setting->updated_at ?? '';
            $token = $setting->oauth_token ?? '';
            $currentDate = date('Y-m-d H:i:s');

            if (empty($token))
            {
                $setting->oauth_token = Str::random(70);
                $setting->save();
            }
            return response()->json([
                'success' => true, 'Token' => $setting->oauth_token
    ]);
        }else{
            $newSetting=new Setting;
            $newSetting->oauth_token = Str::random(70);
            $newSetting->save();
            return response()->json([
                'success' => true, 'Token' => $newSetting->oauth_token
    ]);
    }



        // $untillDate = date('Y-m-d h:m:s', strtotime($updated_at . ' + 1 days'));

        // if ($currentDate > $untillDate)
        // {
        //     $setting->oauth_token = Str::random(70);
        //     $setting->save();
        // }


    }

}
