<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\UserRoles;
use App\ImplementationService\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function deactivateuser(Request $request){



        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [

            'selecteduserid' => 'required|int|exists:users,id',


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


        if($request->user()->userroleid == UserRoles::FlairAdmin || $request->user()->userroleid == UserRoles::CompanyAdmin){


            if($request->selecteduserid == $request->user()->id){


                return response(array('responsemessage'=>'You cannot deactivate yourself'),401);

            }

            $userservice = new UserService();

            if($request->user()->userroleid == UserRoles::CompanyAdmin){

                $selecteduser = User::find($request->selecteduserid);


                if($selecteduser->employerid == $request->user()->employerid){


                   return $userservice->deactivateuser($allkeys);

                }else{


                    return response(array('responsemessage'=>'You do now own this user. Cannot Deactivate'),401);
                }

            }


            return $userservice->deactivateuser($allkeys);

        }
    }



    public function activateuser(Request $request){



        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [

            'selecteduserid' => 'required|int|exists:users,id',


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


        if($request->user()->userroleid == UserRoles::FlairAdmin || $request->user()->userroleid == UserRoles::CompanyAdmin){


            if($request->selecteduserid == $request->user()->id){




            }

            $userservice = new UserService();

            if($request->user()->userroleid == UserRoles::CompanyAdmin){

                $selecteduser = User::find($request->selecteduserid);


                if($selecteduser->employerid == $request->user()->employerid){


                    return $userservice->activateuser($allkeys);

                }else{


                    return response(array('responsemessage'=>'You do now own this user.'),401);
                }

            }


            return $userservice->activateuser($allkeys);

        }
    }



    public function deleteuser(Request $request){



        $request->request->add($this->GetUserAgent($request));


        $validator = Validator::make($request->all(), [

            'selecteduserid' => 'required|int|exists:users,id',


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


        if($request->user()->userroleid == UserRoles::FlairAdmin || $request->user()->userroleid == UserRoles::CompanyAdmin){


            if($request->selecteduserid == $request->user()->id){


            }

            $userservice = new UserService();

            if($request->user()->userroleid == UserRoles::CompanyAdmin){

                $selecteduser = User::find($request->selecteduserid);


                if($selecteduser->employerid == $request->user()->employerid){


                    return $userservice->deleteuser($allkeys);

                }else{


                    return response(array('responsemessage'=>'You do now own this user.'),401);
                }

            }


            return $userservice->deleteuser($allkeys);

        }
    }




}
