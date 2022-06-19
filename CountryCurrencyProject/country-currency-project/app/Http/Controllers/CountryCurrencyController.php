<?php

namespace App\Http\Controllers;


use App\Helper\CharacterFormatHelper;
use App\Helper\Constants;
use App\ImplementationService\CountryCurrencyImplementationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CountryCurrencyController extends Controller
{
    /**
     *method for searching country and currency
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchCountryOrCurrency(Request $request){

        //initialise variables
        $response = null;

        $countrycurrencyimplamentationservice = new CountryCurrencyImplementationService();



        //start validation
        $validator = Validator::make($request->all(), [

            'currency_iso_code' => 'nullable|string|max:3',
            'currency_iso_numeric_code' => 'nullable|string|max:1000',
            'currency_common_name' => 'nullable|string|max:60',
            'currency_official_name' => 'nullable|string|max:60',
            'currency_symbol' => 'nullable|string|max:5',

            'country_continent_code' => 'nullable|string|max:2',
            'country_currency_code' => 'nullable|string|max:3',
            'country_iso3_code' => 'nullable|string|max:3',

            'country_common_name' => 'nullable|string|max:60',
            'country_official_name' => 'nullable|string|max:60',
            'country_endonym' => 'nullable|string|max:60',
            'country_demonym' => 'nullable|string|max:60',

            'current_page_number' => 'nullable|int|max:300',
            'number_of_rows_per_page' => 'nullable|int|max:100',

        ]);

        if ($validator->fails())
        {

            $responsevalues = ["responsemessage"=>"Validation Errors",
                                "errors"=>$validator->errors()->toArray()];

            //validation failed
            return response($responsevalues,400);

        }
        //end validation

        //set values for response message array
        $response["result"] = $countrycurrencyimplamentationservice->listCountriesAndCurrenciesImplementation($request->all());

        $response["guid"] = $request->request_guid;

        //successful response
        return response($response,200);
    }

    /**
     *method for setting up data after migration
     *
     * @param Request $request
     *
     * @return Response
     */


}
