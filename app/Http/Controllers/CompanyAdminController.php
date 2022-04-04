<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\GenerateRandomCharactersHelper;
use App\ImplementationService\AuthenticationService;
use App\ImplementationService\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyAdminController extends Controller
{





    public function listcompanyreps (Request $request) {

        $usermanagementservice = new UserService();

        $request->request->add($this->GetUserAgent($request));


        if($request->user()->userroleid==UserRoles::FlairAdmin){




            return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'Company Admins List',
                'details' => $usermanagementservice->listcompanyreps($request->rowsperpage,$request->page,$request->search,$request->order));



        }

        if($request->user()->userroleid==UserRoles::CompanyAdmin){




            return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'My Company Admins List',
                'details' => $usermanagementservice->listmycompanyreps($request->rowsperpage,$request->page,$request->search,$request->order,$request->user()->employerid));



        }



        return response(array('responsemessage'=>'Unauthorized Access. Flair and Company Admins only'),401);



    }





    public function listmycompanyreps (Request $request) {

        if($request->user()->userroleid!=UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Unauthorized Access. Flair Admins Only'),401);


        }

        $request->request->add($this->GetUserAgent($request));

        $usermanagementservice = new UserService();


        return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'Company Admins List',
            'details' => $usermanagementservice->listcompanyreps($request->rowsperpage,$request->page,$request->search,$request->order));





    }


}
