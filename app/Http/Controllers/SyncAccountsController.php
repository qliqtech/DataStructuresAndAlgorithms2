<?php

namespace App\Http\Controllers;

use App\DBOperations\UserDBOperations;
use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\UserRoles;
use App\Helper\NSSApis;
use App\ImplementationService\BaseImplemetationService;
use App\ImplementationService\CompanyService;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SyncAccountsController extends Controller
{

    public function searchcompany(Request $request){

        $nssapi = new NSSApis("");

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();

        $validator = Validator::make($request->all(), [
            'companyname' => 'required|string|max:60',
         //   'regionid' => 'required|string|max:60',
         //   'districtid' => 'required|string|max:60',
        ]);

        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }


        $responsevalues = $nssapi->searchcompanies($request->companyname,$request->regionid,$request->districtid);


        if(!array_key_exists('data',$responsevalues)){


            return response($responsevalues,404);



        }

        return $responsevalues;


    }




    public function submitcompanyrequest(Request $request){

        $companyservice = new CompanyService();

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();

        $validator = Validator::make($datafromrequest, [
            'companyid' => 'required|string|max:6',
         //   'emailaddress'=>'',
            'authorizationletter_url' => 'required|string',
        ]);

        $datafromrequest["email"] = $request->user->email;

        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }




         $companyservice->submitcompanyrequest($datafromrequest);


        $companystatus = $companyservice->checkcompanystatus($request->user->employerid);


        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Request Submitted Successfully',
            'companystatus'=>$companystatus
            );



    }

    public function addusertonss(Request $request){



        $datafromrequest = $request->json()->all();

        $validator = Validator::make($datafromrequest, [
            'email' => 'required|string|email',

        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }

        $userdbops = new UserDBOperations(new User());


        $userdetails = $userdbops->findUserByEmail($datafromrequest["email"]);


        if($userdetails==null){


            return response(array('responsemessage'=>'email does not exist'));




        }

        $companydetails = DB::connection('onboarding_connection')->table("companies")->find($userdetails->employerid);

        $nssapi = new NSSApis($request->user->nsssyncapikey);

        $response =  $nssapi->adduseragentaccount("test",$userdetails->fullname,$userdetails->email,$userdetails->phonenumber,$companydetails->requestedcompanyid);

      //  dd($response);

       if($response["status"] == false){


           return response($response,500);
       }

       //now verify account and provide API key


        $userauthresponse = $nssapi->authenticateuser($userdetails->email);


        if($userauthresponse["status"] == false){


            $this::StopProcessAndDisplayMessage(401,"user not verified on NSS");
        }


        $userdbops->updateById($userdetails->id,array('nsssyncapikey'=>$userauthresponse['ua_api_key'],
                                                        'isverifiedasuseragent'=>true));




        return response($response);


    }


    public function viewcompanydetailsforapproval(Request $request){

        $companyservice = new CompanyService(null);

        $baseservice = new BaseImplemetationService();


        //   dd($request);

        $request->request->add($this->GetUserAgent($request));


      //  dd($request->user());

        if($request->user->userroleid == UserRoles::FlairAdmin || $request->user->userroleid == UserRoles::NSSAdministrator) {



            $datafromrequest = $this->GetUserAgent($request);

            if ($request->companyid == null) {


                return response("companyid is mandatory", 400);

            }

            $datafromrequest["companyid"] = $request->companyid;


            return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'company details',
                'details' => $companyservice->viewcompanydetialsforapproval($datafromrequest));


        }else{

            $baseservice->StopProcessAndDisplayMessage(401, "Unauthorised access");

        }
    }




    public function listcompaniesfornssapproval(Request $request){

        $companyservice = new CompanyService(null);



        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [
            'rowsperpage' => 'int',
            'page' => 'int',
            'order' => 'string',
            'search' => 'string'
            //   'districtid' => 'required|string|max:60',
        ]);

        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }


        if($request->user->userroleid == UserRoles::NSSAdministrator) {


           // $datafromrequest = $this->GetUserAgent($request);


            return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'company details',
                'details' => $companyservice->listcompaniesfornssapproval($request->rowsperpage,$request->page,$request->search,$request->order));


        }else{

            $responsemessage = array('responsecode'=>401,
                                     'responsemessage'=>'Unauthorised access: NSS admins only' )   ;

            return response($responsemessage,401);

        }

    }





    public function listcompaniesforflairapproval(Request $request){

        $companyservice = new CompanyService();

        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [
            'rowsperpage' => 'int',
            'page' => 'int',
            'order' => 'string',
            'search' => 'string'
            //   'districtid' => 'required|string|max:60',
        ]);

        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }


        if($request->user->userroleid == UserRoles::FlairAdmin) {


            // $datafromrequest = $this->GetUserAgent($request);





            return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'company details',
                'details' => $companyservice->listcompaniesforflairapproval($request->rowsperpage,$request->page,$request->search,$request->order));

        }else{

            $responsemessage = array('responsecode' => 401,
                'responsemessage'=>'Unauthorised access: Flair admins only');

            return response($responsemessage,401);

        }

    }






    public function nssapproveorrejectcompany(Request $request){

        $companyservice = new CompanyService();

        $request->request->add($this->GetUserAgent($request));




        $datafromrequest = $request->json()->all();

        $validator = Validator::make($datafromrequest, [
            'companyid' => 'required|string|max:6',
            'approvalstatus'=>'required|int'
        ]);



        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }

        $datafromrequest['approvalstatus'] = (int)$datafromrequest['approvalstatus'];


        // dd($datafromrequest['approvalstatus']);

        if($datafromrequest['approvalstatus']<2){


            return response('response must be 2 OR 3',401);

        }

        if($datafromrequest['approvalstatus']>3){


            return response('response must be 2 OR 3',401);

        }

        if($request->user->userroleid == UserRoles::NSSAdministrator){

            $companyservice->nssapproveorrejectcompanyrequest($datafromrequest);

        }
        else{

            $companyservice->StopProcessAndDisplayMessage(401,"Unauthorised Access: NSS Admins only");
        }





        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Complete',
        );



    }




    public function flairapproveorrejectcompany(Request $request){

        $companyservice = new CompanyService();

        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $request->json()->all();

        $validator = Validator::make($datafromrequest, [
            'companyid' => 'required|string|max:6',
            'approvalstatus'=>'required|int'
        ]);

        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()
            );


            return response($responsevalues,400);


        }

        $datafromrequest['approvalstatus'] = (int)$datafromrequest['approvalstatus'];


       // dd($datafromrequest['approvalstatus']);

        if($datafromrequest['approvalstatus'] < 2){


            return response('response must be 2 OR 3',401);

        }

        if($datafromrequest['approvalstatus'] > 3){


            return response('response must be 2 OR 3',401);

        }

        if($request->user->userroleid == UserRoles::FlairAdmin){

            $companyservice->flairapproveorrejectcompanyrequest($datafromrequest);

        }
        else{

            $companyservice->StopProcessAndDisplayMessage(401,"Unauthorised Access: Flair Admins only");
        }





        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Complete',
        );



    }




    public function listdistrictsonnss(Request $request){

        $nssapi = new NSSApis("");

        $request->request->add($this->GetUserAgent($request));


        return $nssapi->listdistrictsonNSS();

    }


    public function checkcompanystatus(Request $request){

        $companyservice = new CompanyService();

        $companyid = $request->user->employerid;

        if($request->user->userroleid != UserRoles::CompanyAdmin){


            return response('unauthorized access',401);
        }

        if($companyid == null){

            return response('user has no company',401);
        }

        return $companyservice->checkcompanystatus($companyid);

    }





    public function listregionsonnss(Request $request){

        $nssapi = new NSSApis("");

        $request->request->add($this->GetUserAgent($request));

        return $nssapi->listregionsonNSS();

    }







}
