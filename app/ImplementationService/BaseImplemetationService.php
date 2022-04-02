<?php

namespace App\ImplementationService;

use App\Enums\InAppResponsTypes;
use App\Http\Controllers\Controller;

class BaseImplemetationService extends Controller
{

    public function responseHelper($response): array {


        if($response == null){

            return array([InAppResponsTypes::responsetypekey=>InAppResponsTypes::Error]);
        }
        if(!array_key_exists(InAppResponsTypes::responsetypekey, $response)){

            return array(InAppResponsTypes::responsetypekey =>InAppResponsTypes::ValidationError);

        }

        $auditparams = $response["AuditItems"];


      //  dd($auditparams);

        $this::LogAudit($auditparams);

        return array($response);



    }

    public function LogAudit($parameters){


     //   dd($parameters);
        $auditservice = new AuditService();

        $auditservice->SaveAuditInDB($parameters);


    }

    public function StopProcessAndDisplayMessage($httpcode,$displaymessage){


        header('Content-type: application/json');

        header("HTTP/1.1 ".$httpcode);

        $errormessage = array('responsecode'=>$httpcode,
                            'responsemessage'=>$displaymessage
            );

     //   dd($errormessage);


        $errormessage = json_encode($errormessage,true);

        echo $errormessage; die();




    }

}
