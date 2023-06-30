<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check())
        {
            $user = Auth::user();
            if ($user->hasRole('Admin'))
            {
                return redirect('/admin/dashboard');
            } else if ($user->hasRole('IotAdmin'))
            {

                return redirect('/iot-admin/dashboard');
            } else
            {

                return redirect('/home');
            }
        }

        return $next($request);
    }

}
