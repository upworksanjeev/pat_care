<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
class LoginController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        $user = Auth::user();
        if ($user->hasRole('Admin'))
        {
            return '/admin/dashboard';
        } else if ($user->hasRole('IotAdmin'))
        {

            return '/iot-admin/dashboard';
        } else
        {
            Auth::logout();
            Session::flash('message', 'User does not have the right permissions'); 
            Session::flash('alert-class', 'alert-danger'); 
            return '/admin';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('Admin'))
        {
            $redirect =  '/admin';
        } else if ($user->hasRole('IotAdmin'))
        {

            $redirect = '/iot-admin';
        } else
        {

            $redirect  = '/';
        }
        Auth::logout();
        return redirect($redirect);
    }

}
