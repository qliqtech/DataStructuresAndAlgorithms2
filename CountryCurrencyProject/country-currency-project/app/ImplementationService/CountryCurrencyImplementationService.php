<?php

namespace App\ImplementationService;


use App\Helper\CharacterFormatHelper;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\QueryException;
use Mockery\Exception;
use function PHPUnit\Framework\throwException;

/**
 * Handling logic between params sent from controller and Model
 *
 */


class CountryCurrencyImplementationService
{

    private $currencyModel = null;
    private $countryModel = null;



    public function __construct(){

        $this->currencyModel = new Currency();
        $this->countryModel = new Country();

    }
    /**
     *implements searches based on parameters sent
     *
     * @param array $parameters
     *
     * @return array
     */

    public function listCountriesAndCurrenciesImplementation(array $parameters):array{

        $numberofrowsperpage = 15;

        $currentpagenumber = 1;


        //removing all unwanted data
        foreach($parameters as $key=>$value)
        {
            if(is_null($value) || $value == '')
                unset($parameters[$key]);
        }



        $query = $this->countryModel->listCountriesAndCurrencies();


        if(array_key_exists('currency_iso_code',$parameters)){


            $query = $query->orHaving('currency_iso_code', '=',$parameters["currency_iso_code"]);

        }
        if(array_key_exists('currency_iso_numeric_code',$parameters)){


            $query = $query->orHaving('currency_iso_numeric_code', '=',$parameters["currency_iso_numeric_code"]);

        }
        if(array_key_exists('currency_common_name',$parameters)){


            $query = $query->orHaving('currency_common_name', 'LIKE','%'.$parameters["currency_common_name"].'%');

        }
        if(array_key_exists('currency_official_name',$parameters)){


            $query = $query->orHaving('currency_official_name', 'LIKE', '%'.$parameters["currency_official_name"].'%');

        }

        if(array_key_exists('currency_official_name',$parameters)){


            $query = $query->orHaving('currency_official_name', 'LIKE', '%'.$parameters["currency_official_name"].'%');

        }
        if(array_key_exists('country_continent_code',$parameters)){


            $query = $query->orHaving('country_continent_code', '=',$parameters["country_continent_code"]);

        }

        if(array_key_exists('country_common_name',$parameters)){


            $query = $query->orHaving('country_common_name', 'LIKE','%'.$parameters["country_common_name"].'%');

        }
        if(array_key_exists('country_iso3_code',$parameters)){


            $query = $query->orHaving('country_iso3_code', '=',$parameters["country_iso3_code"]);

        }
        if(array_key_exists('country_currency_code',$parameters)){


            $query = $query->orHaving('country_currency_code', '=',$parameters["country_currency_code"]);

        }

        //


        //end query building

        //prepare pagination parameters
        if(array_key_exists('number_of_rows_per_page',$parameters)){


            $numberofrowsperpage = $parameters["number_of_rows_per_page"];

        }
        if(array_key_exists('current_page_number',$parameters)){


            $currentpagenumber = $parameters["current_page_number"];

        }


        //execute query and paginate
        $data = $query->paginate($numberofrowsperpage,['*'],'page',$currentpagenumber);
        //end execute an paginate

        //

        return $data->toArray();

    }
    /**
     *loads data from currency file into Db
     *loads
     */
    public function uploadCurrencyIntoDb(): bool{


       $datafromfile =  CharacterFormatHelper::csvToArray(storage_path('/app/01-Currencies.csv'));


            $counter = 0;

            for ($i = 0; $i < count($datafromfile); $i++) {

                // to solve issue of  Malformed UTF-8 characters
            //    $datatoinsert = CharacterFormatHelper::convertFromLatin1toutf8Recursively($datafromfile[$i]);


                Currency::updateOrCreate(['iso_code'=>$datafromfile[$i]['iso_code']],$datafromfile[$i]);

                $counter++;

            }

        return true;

    }

    /**
     *loads data from countries file into Db
     *loads
     */
    public function uploadCountryIntoDb() : bool{

        $datafromfile =  CharacterFormatHelper::csvToArray(storage_path('/app/02-Countries.csv'));



        $counter = 0;
       //     dd($datafromfile);


            for ($i = 0; $i < count($datafromfile); $i++) {

                // to solve issue of  Malformed UTF-8 characters
             //   $datatoinsert = CharacterFormatHelper::convertFromLatin1toutf8Recursively($datafromfile[$i]);


                Country::updateOrCreate(['iso3_code'=>$datafromfile[$i]['iso3_code']],$datafromfile[$i]);
                $counter++;
            }

            return true;


       // return false;






    }

}
