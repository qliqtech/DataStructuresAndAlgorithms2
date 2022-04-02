<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\GenerateRandomCharactersHelper;
use App\ImplementationService\AuthenticationService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyAdminController extends Controller
{



    public function sendinvite (Request $request) {

        if($request->user()->userroleid!=UserRoles::CompanyAdmin){


            return response(array('responsemessage'=>'Unauthorized Access. Company Admins Only'),401);


        }

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [

            'email' => 'required|string|email|max:255|unique:users',
         //   'fullname' => 'required|string|max:50',


        ]);




        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()

            );
            //  dd($validator->errors());


            return response($responsevalues,400);


        }





        $service = new AuthenticationService();

        $datafromrequest['confirmationcode'] = GenerateRandomCharactersHelper::generaterandomAlphabets(20);


        $datafromrequest['employerid'] = $request->user()->employerid;

        $response = $service->registercompanyrep($datafromrequest);



        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Invitation sent successfully. '.$datafromrequest['confirmationcode']

            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Error',



        );


        return response($responsevalues,500);


    }



    public function getuserdetailsfromconfirmationcode(Request $request){

        $confirmationcode = $request->confirmationcode;

        $user = User::where('confirmationcode','=',$confirmationcode)->first();

        if($user == null){

            return response(array('responsemessage'=>'code not found'),404);

        }

        $authenticationservice = new AuthenticationService();

        $usermeta = $authenticationservice->userinfo($user->id);

        return response(array('usermeta'=>$usermeta));
    }



    public function registercompanyrep(Request $request){

        $confirmationcode = $request->confirmationcode;

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [
            'fullname' => 'required|string|max:255',
            'confirmationcode' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'phonenumber' => 'required|string|Max:15',
            'jobroleid' => 'required|int|exists:jobroles,id',

        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()

            );
            return response($responsevalues,400);


        }



        $service = new AuthenticationService();

     //   $datafromrequest['confirmationcode'] = GenerateRandomCharactersHelper::generaterandomAlphabets(20);


        $response = $service->completecompanyrepregistration($datafromrequest);



        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Registration successfull',
                'token'=>$response["token"],
                'usermeta'=>$response["usermeta"]

            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Error',



        );


        return response($responsevalues,500);

    }


}
