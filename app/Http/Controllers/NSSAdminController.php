<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\GenerateRandomCharactersHelper;
use App\Helper\NSSApis;
use App\ImplementationService\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NSSAdminController extends Controller
{



    public function sendinvite (Request $request) {

        if($request->user()->userroleid!=UserRoles::SuperAdmin){


            return response(array('responsemessage'=>'Unauthorized Access. Flair Admins Only'),401);


        }

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [

            'email' => 'required|string|email|max:255|unique:users',
            'fullname' => 'required|string|max:50',


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


        $nssapi = new NSSApis("");

        $verificationresultonnss = $nssapi->getuserdetailsfromNSS($datafromrequest["email"]);


        if($verificationresultonnss["status"] == false){


            return response(array('responsemessage'=>'user details not found on NSS'),404);

        }

     //   dd($verificationresultonnss);


        if($verificationresultonnss["role"] != "Administrator"){

            return response(array('responsemessage'=>'user is not an admin on NSS'),401);

        }



        $service = new AuthenticationService();

        $datafromrequest['confirmationcode'] = GenerateRandomCharactersHelper::generaterandomAlphabets(20);




      //  $userauthresponse = $nssapi->authenticateuser($datafromrequest["email"]);


     //   dd($userauthresponse);

     //   if($userauthresponse["status"] == false){


     //       return response(array('responsemessage'=>'failed'),401);

     //   }





        $datafromrequest["nsssyncapikey"] = "--";


        $response = $service->registernssadmin($datafromrequest);



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


}
