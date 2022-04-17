<?php

namespace App\Http\Middleware;

use App\Helper\InternalAPIs;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //make api call from here by querying db
        //
        //ge

        $internalapi = new InternalAPIs("");

        $token= request()->bearerToken();





        $response = $internalapi->getuserdetails($token);

        if($response == null){

            $message = ["responsemessage" => "Authentication failed"];

            return response($message, 401);


        }

        $response['bearertoken'] = $token;


        $request->merge(['user' => (object)$response]);

        //   if (Auth::guard('api')->check() ) {


        //   dd($request);

        if($response['IsActive'] == false){

            $message = ["message" => "User Deactivated"];
            return response($message, 401);

        }else{

            //   dd('meme');
            return $next($request);

        }

        //

    }

}
