<?php


namespace App\Helper;




class TimeHelper
{

    public static function getCurrentDateTime(){


        return \Carbon\Carbon::now()->toDateTimeString();
    }

    public static function getFirstDayOfMonth(){


        $startDate = \Carbon\Carbon::now(); //returns current day
        $firstDay = $startDate->firstOfMonth();

        return $firstDay;
    }

    public static function getCurrentDateAndAddDays($numberofdaystoadd){


        $startDate = \Carbon\Carbon::now(); //returns current day
        $nestday = $startDate->addDays($numberofdaystoadd);

        return $nestday;
    }

    public static function getCurrentDayforbatchgeneration(){

//Y-m-d
        return \Carbon\Carbon::now()->format('d-m-y');



    }
}
