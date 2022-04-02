<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Helper\GenerateRandomCharactersHelper;
use App\Http\Controllers\Controller;
use App\ImplementationService\AuthenticationService;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class ApiAuthController extends Controller
{

    public function register (Request $request) {

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
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
          //  dd($validator->errors());


            return response($responsevalues,400);


        }



        $service = new AuthenticationService();

        $datafromrequest['confirmationcode'] = GenerateRandomCharactersHelper::generaterandomAlphabets(20);


        $response = $service->registeruser($datafromrequest);



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


    public function login (Request $request) {

        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [

            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues);


        }
        $allkeys = $request->all();


        $service = new AuthenticationService();

        $response = $service->login($allkeys);

        //  dd($response);

        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Login successfull',
                'usertoken'=>$response[InAppResponsTypes::responsemessagekey],
                'usermeta'=>$response['usermeta']


            );


            return response($responsevalues);
        }else{

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Wrong username or password',



            );

            return response($responsevalues,401);


        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
                                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>$response[InAppResponsTypes::responsemessagekey],



        );


        return response($responsevalues);


    }


    public function userinfo (Request $request) {

        $request->request->add($this->GetUserAgent($request));


        $allkeys = $request->all();

        $service = new AuthenticationService();

        $response = $service->userinfo($request->user()->id);

        return $response;
    }





    public function confirmaccount (Request $request) {

        $request->request->add($this->GetUserAgent($request));

        $i = 2;

        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [
            'confirmationcode' => 'required|string',

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

        $response = $service->confirmaccount($datafromrequest);




        return response($response,200);


    }



    public function sendpasswordresetlink (Request $request) {

        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [

            'email' => 'required|string|email',

        ]);
        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues);


        }
        $allkeys = $request->all();


        $service = new AuthenticationService();

        $response = $service->sendpasswordresetlink($allkeys);

        //  dd($response);

        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


         //   'confirmationcodefortest'=>$confirmationcode

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Password reset link sent',
                    'confirmationcodefortest'=>$response['confirmationcodefortest']


            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>$response[InAppResponsTypes::responsemessagekey],



        );


        return response($responsevalues);


    }



    public function resetpassword (Request $request) {

        $request->request->add($this->GetUserAgent($request));



        $datafromrequest = $request->json()->all();



        $validator = Validator::make($datafromrequest, [
            'password' => 'required|string|min:6',
            'confirmationcode' => 'required|string',
        ]);


        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::ValidationError,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }

        if($datafromrequest['password']!=$datafromrequest['confirmpassword']){


            return response('Password and confirmed password not match',400);

        }


        $service = new AuthenticationService();

        $response = $service->resetpassword($datafromrequest);



        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                'responsemessage'=>'Account password reset',
                'usertoken'=>$response[InAppResponsTypes::responsemessagekey]


            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Error',



        );


        return response($responsevalues,500);


    }



    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


    public function authenticationerror(){

        $response = ['message' => 'Authentication Error'];
        return response($response, 200);

    }

    public function testredis(){


            $redis = Redis::connection();

        //    $redis->set('dashboarddetails_'.'666', 'test me');

           $value =  $redis->get('dashboarddetails_'.'666');

        $allKeys = $redis->keys('*');

        dd($allKeys);


        if($request->user()->lastdashboardvisit == null){


                $service = new ReportsService();

                return  $response =  $service->dashboardadministrationsummary($request);


            }

            $currentTime = Carbon::now();

            if($currentTime->diffInHours($request->user()->lastdashboardvisit) > 1){

                //   dd("updating redis");

                $service = new ReportsService();

                $response =  $service->dashboardadministrationsummary($request);


                $redis->set('dashboarddetails_'.$userid, json_encode($response));

                //  echo "setting redis";die();



                return $response;

            }

            $response = $redis->get('dashboarddetails_'.$userid);




            return $response;

        }



}
