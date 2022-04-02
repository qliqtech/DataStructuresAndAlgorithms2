<?php

namespace App\DBOperations;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserDBOperations extends BaseDBOperations
{

    protected $model;

    public function findUserByEmail($emailaddress): ?Model
    {
        return User::where('email',$emailaddress)->first();
    }

    public function updateuseraccounttoconfirmed($confirmationcode): ?Model
    {
       $userdetails = User::where('confirmationcode', $confirmationcode)->first();

       if($userdetails==null){


           return null;
       }

        User::where('confirmationcode', $confirmationcode)
            ->update([
                'ConfirmedOn' => now(),
                'IsConfirmed'=>true
            ]);

       return $userdetails;
    }




    public function updateuseraccountpasswordfromconfirmationcode($confirmationcode,$newpassword): ?Model
    {
        $userdetails = User::where('confirmationcode', $confirmationcode)->first();

        if($userdetails==null){


            return null;
        }

        User::where('confirmationcode', $confirmationcode)
            ->update([
                'password' =>$newpassword,
                'requirespasswordreset'=>false
            ]);

        return $userdetails;
    }


    public function updateuseraccountbyconfirmationcode($confirmationcode,$attributes): ?Model
    {
        $userdetails = User::where('confirmationcode', $confirmationcode)->first();

        if($userdetails==null){


            return null;
        }

        User::where('confirmationcode', $confirmationcode)
            ->update($attributes);

        return $userdetails;
    }


}
