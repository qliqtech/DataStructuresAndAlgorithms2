<?php


namespace App\Http\Controllers;


use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Helper\FlairInternalAPIs;
use App\Helper\GenerateRandomCharactersHelper;
use App\Helper\GhanaPostAPI;
use App\Helper\NSSApis;
use App\ImplementationService\AuthenticationService;
use App\ImplementationService\BaseImplemetationService;
use App\ImplementationService\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class CandidateOnboardingController extends Controller
{

    public function searchnspwithnssnumberanddob(Request $request){




       // $datafromrequest = $request->json()->all();

        $validator = Validator::make($request->all(), [
            'nssnumber' => 'required|string|max:15',
            //   'emailaddress'=>'',
            'dob' => 'required|string',
        ]);

     //  $datafromrequest["email"] = $request->user()->email;

        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }

        $flairapi = new FlairInternalAPIs("");

        $nspdetails = $flairapi->getNSPDetailsbyNSSnumber($request->nssnumber);

        if($nspdetails == null){



            $responsemessage =  array('responsecode'=>404,
                'responsemessage'=>'NSS number not found');

            return response($responsemessage,404);


        }


        $dobsplit = explode("-", $request->dob);

        if(count($dobsplit)!=3){

            $responsemessage =  array('responsecode'=>404,
                'responsemessage'=>'Invalid DOB:'.$request->dob );

            return response($responsemessage,400);

        }

         if($dobsplit[0] == $nspdetails["regd_dob_day"] &&
            $dobsplit[1] == $nspdetails["regd_dob_month"] &&
            $dobsplit[2] == $nspdetails["regd_dob_year"]){


             return response($nspdetails,200);


         }

        $responsemessage =  array('responsecode'=>404,
            'responsemessage'=>'No match found');

        return response($responsemessage,404);

    }



    public function registercandidate (Request $request) {

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [

            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'fullname' => 'required|string|min:3',
            'phonenumber' => 'required|string|Max:15',
            'nssnumber' => 'required|string',

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


        $response = $service->registercandidate($datafromrequest);



        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Registration successfull. Confirmation link sent to your email: '.$datafromrequest['confirmationcode']

            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Error',



        );


        return response($responsevalues,500);


    }


}
