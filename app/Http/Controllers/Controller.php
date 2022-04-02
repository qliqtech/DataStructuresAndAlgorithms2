<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function GetUserAgent(Request $request){

        $userid = null;

        $useremail = null;

        if($request->user() != null){

            $userid = $request->user()->id;

            $useremail = $request->email;
        }


        $useragent = ['userip'=>$request->ip(),
            'userid'=>$userid,
            'created_by'=>$userid,
            'useremail'=>$useremail,
            'requesturl'=>$request->fullUrl(),
            'browser'=>$request->userAgent(),
            'requestbody'=>$request->getContent()];

        return $useragent;

    }
}
