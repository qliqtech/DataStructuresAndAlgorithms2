<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\ImplementationService\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{




    public function createassignment (Request $request) {

        $request->request->add($this->GetUserAgent($request));


        $data = $request->json()->all();

        $service = new AssignmentService($data);


        $response = $service->createassignment();


        $validator = Validator::make($request->all(), [

            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|max:15',
        ]);
        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }
        $allkeys = $request->all();


        $service = new AssignmentService();

        $response = $service->createassignment($allkeys);

        //  dd($response);

        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Assignment Created',



            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>$response[InAppResponsTypes::responsemessagekey],



        );


        return response($responsevalues);


    }





    public function markassignmentandcomment (Request $request) {

        $request->request->add($this->GetUserAgent($request));



        $allkeys = $request->all();


        $service = new AssignmentService();

        $response = $service->markassignmentandcomment($allkeys);

        //  dd($response);

        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Assignment Marked',



            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>$response[InAppResponsTypes::responsemessagekey],



        );


        return response($responsevalues);


    }






    public function submitassignment (Request $request) {

        $request->request->add($this->GetUserAgent($request));



        //attempt fileupload on AWS and provideurl

      if(strtolower($request->file('fileupload')->getClientOriginalExtension()) != 'zip'){


          echo "Upload Zip Files Only";die();

      }


        $filename = $request->file('fileupload')->getClientOriginalName();



        $storagePath = Storage::disk('s3')->putFileAs('assignments/'.$request->user()->email,$request->file('fileupload'),$filename ,'public');

        $bucketbasepath = env('AWS_BASEURLSOURCEHUB', 'aaa');


        $request->request->add(array('answerurl'=> $bucketbasepath.$storagePath));


        $allkeys = $request->all();


        $service = new AssignmentService();




        $response = $service->submitassignment($allkeys);

        //  dd($response);

        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Assignment Submitted',

            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>$response[InAppResponsTypes::responsemessagekey],



        );


        return response($responsevalues);


    }





    public function listassigments (Request $request) {

        $request->request->add($this->GetUserAgent($request));



        $allkeys = $request->all();


        $service = new AssignmentService();

        $response = $service->createassignment($allkeys);

        //  dd($response);

        if($response[InAppResponsTypes::responsetypekey] == InAppResponsTypes::Success){


            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Assignment Created',



            );


            return response($responsevalues);
        }


        $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::FailedCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>$response[InAppResponsTypes::responsemessagekey],



        );


        return response($responsevalues);


    }



}
