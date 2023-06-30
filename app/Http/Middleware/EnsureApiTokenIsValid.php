<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;


class EnsureApiTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Token');
        $setting = Setting::orderBy('id', 'asc')->first();
        $currentDate = date('Y-m-d H:i:s');

        if($header && $setting)
        {
            $token = $setting->oauth_token ?? '';
            $updated_at = $setting->updated_at;
            if($header == $token)
            {
                // $untillDate=  date('Y-m-d h:m:s', strtotime($updated_at. ' + 1 days'));

                // if($currentDate > $untillDate)
                // {
                //     return response()->json(['success' => false , 'message' => "Api Access Token has been Expired"],400);
                // }

                return $next($request);
            }
        }
        return response()->json(['success' => false , 'message' => "Invailed Api Access Token"],400);

    }
}
