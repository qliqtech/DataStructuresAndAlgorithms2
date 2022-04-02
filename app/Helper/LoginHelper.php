<?php

namespace App\Helper;

use Illuminate\Support\Facades\Hash;

class LoginHelper
{

    public static function HashPassWord($password): string {

        if($password == null){


            die('Password field is empty');
            //return "";

        }

        return Hash::make($password);


    }



    public static function PasswordCheck($password1,$password2): bool {



        return Hash::check($password1,$password2);



    }

}
