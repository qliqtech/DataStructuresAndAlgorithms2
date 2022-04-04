<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\GenerateRandomCharactersHelper;
use App\ImplementationService\AuthenticationService;
use App\ImplementationService\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FlairAdminController extends Controller
{








    public function listflairadmins (Request $request) {

        if($request->user()->userroleid!=UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Unauthorized Access. Flair Admins Only'),401);


        }

        $request->request->add($this->GetUserAgent($request));

        $usermanagementservice = new UserService();


        return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'Flair Admins List',
            'details' => $usermanagementservice->listflairadmins($request->rowsperpage,$request->page,$request->search,$request->order));





    }




}
