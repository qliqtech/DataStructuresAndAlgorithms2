<?php

namespace App\CacheOperations;

use Illuminate\Support\Facades\Redis;

class CacheUserRegistrationOps
{




    public function cacheuserregistrationdetails($confirmationcode,$registrationparams){



        Redis::set('confirmationcode_' . $confirmationcode, json_encode($registrationparams) );


    }


    public function getuserregistrationdetailsfromcache($confirmationcode){


       if(Redis::exists('confirmationcode_' . $confirmationcode)){

           return  Redis::get('confirmationcode_' . $confirmationcode);

       }
       return null;


    }

    public function deleteconfirmationcode($confirmationcode){


        Redis::del('confirmationcode_' . $confirmationcode);





    }
}
