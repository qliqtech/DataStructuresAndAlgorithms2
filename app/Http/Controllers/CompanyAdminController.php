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
