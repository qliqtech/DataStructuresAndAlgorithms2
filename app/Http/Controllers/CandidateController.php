<?php


namespace App\Http\Controllers;


use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\FlairInternalAPIs;
use App\Helper\GenerateRandomCharactersHelper;
use App\Helper\GhanaPostAPI;
use App\Helper\NSSApis;
use App\ImplementationService\AuthenticationService;
use App\ImplementationService\BaseImplemetationService;
use App\ImplementationService\CompanyService;
use App\ImplementationService\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class CandidateController extends Controller
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




    public function listcandidates (Request $request) {

        if($request->user->userroleid!=UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Unauthorized Access. Flair Admins Only'),401);


        }

        $request->request->add($this->GetUserAgent($request));

        $usermanagementservice = new UserService();


        return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'Candidates Admins List',
            'details' => $usermanagementservice->listcandidates($request->rowsperpage,$request->page,$request->search,$request->order));





    }


}
