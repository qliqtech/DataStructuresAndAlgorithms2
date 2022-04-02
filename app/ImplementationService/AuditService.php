<?php

namespace App\ImplementationService;

use App\DBOperations\AuditDBOperations;
use App\Models\AuditTrail;

class AuditService extends BaseImplemetationService
{

    public function SaveAuditInDB($attributes): bool{

        try{

            $audit = new AuditTrail();

            $auditdbops = new AuditDBOperations($audit);

            $auditdbops ->create($attributes);


        }catch(\Exception $ex){

         //   echo $ex->getMessage();die();

            return false;

        }

        return true;

    }




}
