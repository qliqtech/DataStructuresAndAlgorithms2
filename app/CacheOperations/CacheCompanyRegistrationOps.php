<?php

namespace App\CacheOperations;

use Illuminate\Support\Facades\Redis;

class CacheCompanyRegistrationOps
{




    public function cachecompanyregistrationdetails($companyid,$registrationparams){


        Redis::set('companydetails_' . $companyid, json_encode($registrationparams) );


    }

    public function getcompanydetailsfromcache($companyid){





        if(Redis::exists('companydetails_' . $companyid)){


         //   dd($companyid);
            return  Redis::get('companydetails_' . $companyid);

        }

     //   echo "nothing here";
        return null;


    }



    public function cachecompanybranchesdetails($companyid, $branches){

        Redis::set('companybranches_' . $companyid, json_encode($branches) );


    }


    public function getcompanybranchesfromcache($companyid){


        if(Redis::exists('companybranches_' . $companyid)){

            return  Redis::get('companybranches_' . $companyid);

        }
        return null;


    }



}
