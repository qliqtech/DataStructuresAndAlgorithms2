<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Helper\GhanaPostAPI;
use App\ImplementationService\BaseImplemetationService;
use App\ImplementationService\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CompanyOnBoardingController extends Controller
{

    public function listindustries(){

        $companyservice = new CompanyService(null);

      return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'List of Company Industries',
            'companyindustrylist'=>$companyservice->listindustries());


    }

    public function listregions(){

        $companyservice = new CompanyService(null);

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'List of Regions',
            'regionlist'=>$companyservice->listregions());



    }



    public function listcompanyjobroles(){

        $companyservice = new CompanyService(null);

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'List of Job Roles',
            'jobroleslist'=>$companyservice->listjobRoles());


    }




    public function viewcompanydetails(Request $request){

        $companyservice = new CompanyService(null);

     //   dd($request);

        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $this->GetUserAgent($request);



        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'company details',
            'companydetails'=>$companyservice->viewcompanydetials($datafromrequest));



    }






    public function listallcompanies(Request $request){

        $companyservice = new CompanyService(null);

        //   dd($request);

        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $this->GetUserAgent($request);



        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'company details',
            'companydetails'=>$companyservice->viewcompanydetials($datafromrequest));



    }


    public function viewbranchdetails(Request $request){


        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $this->GetUserAgent($request);





        $companyservice = new CompanyService(null);

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'branches',
            'branchlist'=>$companyservice->viewcompanybranches($datafromrequest));



    }

    public function listcompanybranches(Request $request){


        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $this->GetUserAgent($request);


        $companyservice = new CompanyService(null);

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'branches',
            'branchlist'=>$companyservice->listbranches($datafromrequest));


    }

    public function verifyghanapostgps(Request $request){



        $datafromrequest = $request->all();



        $validator = Validator::make($datafromrequest, [
            'gpscode' => 'required|string',

        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }


           $ghanapostsearchresult = GhanaPostAPI::VerifyByGPSCode($request->gpscode);


        if($ghanapostsearchresult == null){

              $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Address not found');

                        return response($responsevalues,404);

        }

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'GPS Code Results',
            'ghanapostdetails'=>$ghanapostsearchresult);



    }


    public function verifycompanydetails(Request $request){


        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();




        $validator = Validator::make($datafromrequest["companydetails"], [
            'registeredcompanyname' => 'required|string|max:60|unique:companies',
            'registrationid' => 'required|string|max:20|unique:companies',
            'tin' => 'required|string|max:20|unique:companies',

        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }

      $responsemessage =  array('responsecode'=>200,
                                'responsemessage'=>'its all good');

        return response($responsemessage,200);


    }


    public function registercompany(Request $request){


        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();




        $validator = Validator::make($datafromrequest["companydetails"], [
            'registeredcompanyname' => 'required|string|max:60',
            'tradingcompanyname' => 'required|string|max:60',
            'registrationid' => 'required|string|max:20|unique:companies',
            'tin' => 'required|string|max:20|unique:companies',
            'companytype' => 'required|int|max:2|min:1',
            'industryid' => 'required|exists:company_industries,id',
            'yearfounded' => 'required|string',
            'numberofemployeesrange' => 'required|string',
            'biotagline' => 'required|string',
            'aboutcompany' => 'required|string',
            'companycoverurl' => 'string',
            'companylogourl' => 'string',
            'address' => 'required|string',

            'headquartersdisplayname' => 'required|string|max:60',
         //   'ghanapostaddress' => 'string|max:15',
            'city' => 'string|max:30',

            'regionid' => 'int|required|exists:regions,id',

            'website' => 'string|max:90',

        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }

      //  $values = $this::validateBranches($datafromrequest["branches"]);


     //   if($values!=null){


     //       return $values;

    //    }

        $baseimplementationservice = new BaseImplemetationService();

        $ghanapostsearchresult = GhanaPostAPI::VerifyByGPSCode($datafromrequest["companydetails"]["ghanapostaddress"]);

        if($datafromrequest["companydetails"]['ghanapostaddress']!=null){

            if($ghanapostsearchresult == null){



                  $baseimplementationservice->StopProcessAndDisplayMessage(200,"Invalid Ghana Post Address");


                //    return response("Invalid GPS address",401);
            }

        }



        $companyservice = new CompanyService();

       $response = $companyservice->registercompany($datafromrequest);

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Company registered',

            'companyinfo'=>$response['companyinfo']
            );



    }




    public function completecompanyregistration(Request $request){


        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();




        $validator = Validator::make($datafromrequest["companydetails"], [
          //  'registeredcompanyname' => 'required|string|max:60',
          //  'tradingcompanyname' => 'required|string|max:60',
          //  'registrationid' => 'required|string|max:20|unique:companies',
          //  'tin' => 'required|string|max:20|unique:companies',
               'companytype' => 'required|int|max:2|min:1',
              'industryid' => 'required|exists:company_industries,id',
              'yearfounded' => 'required|string',
              'numberofemployeesrange' => 'required|string',
              'website' => 'string|max:90',
            'website' => 'string|max:90',




        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }



        $companyservice = new CompanyService();

        //  echo"" ($datafromrequest);


        $companyservice->completecompanyregistration($datafromrequest);

        return  $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Company registration completed',
        );



    }




    public function validateBranches($branchlist){


        $numberofbrancheslimit = 10;

        $baseimplementationservice = new BaseImplemetationService();

        if(count($branchlist) > $numberofbrancheslimit){

            $baseimplementationservice->StopProcessAndDisplayMessage("400","Number of branches limit exceeded($numberofbrancheslimit)");

        }

        $count = 0;

        $countheadquaters = 0;

        $hasheadquaters = false;

        foreach ($branchlist as $branch){

            $count++;

            $validator = Validator::make($branch, [
                'branchname' => 'required|string|max:60',
                'ghanapostaddress' => 'string|max:15',
                'city' => 'string|max:30',

                'regionid' => 'int|required|exists:regions,id',
]);


            if ($validator->fails())
            {

                $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                    ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Branch Validation Errors. Error on array number:'.$count,
                    'errors'=>$validator->errors()->toArray()

                );

              //  $baseimplementationservice->StopProcessAndDisplayMessage("400",$validator->errors()->all());


                return response($responsevalues,400);


            }


            $branchname = "";

            if(array_key_exists("isheadquarters", $branch)){


                if($branch["isheadquarters"] == true){

                    $countheadquaters++;

                }
            }

                if($countheadquaters == 0){

                      $baseimplementationservice->StopProcessAndDisplayMessage("400","You did not select a headquarter");
                }

            if($countheadquaters > 1){

                $baseimplementationservice->StopProcessAndDisplayMessage("400","You selected more than one headquarter");
            }

        }

          return  null;
    }


}
