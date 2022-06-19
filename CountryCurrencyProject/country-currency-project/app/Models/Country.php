<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'continent_code', 'currency_code', 'iso2_code','iso3_code','iso_numeric_code','fips_code',
        'calling_code','common_name',
        'official_name','endonym','demonym'
    ];


    public function listCountriesAndCurrencies(): Builder{

        //         //one country can have only one currency
        return Country::leftjoin('currencies', 'currencies.iso_code', '=', 'countries.currency_code')->
                        select('currencies.iso_code AS currency_iso_code',
                               'currencies.iso_numeric_code AS currency_iso_numeric_code',
                                'currencies.common_name AS currency_common_name',
                                'currencies.official_name AS currency_official_name',
                                'currencies.symbol AS currency_symbol',
            'countries.continent_code AS country_continent_code',
            'countries.currency_code AS country_currency_code',
            'countries.iso2_code AS country_iso2_code',
            'countries.iso3_code AS country_iso3_code',
            'countries.iso_numeric_code AS country_iso_numeric_code',
            'countries.fips_code AS country_fips_code',
            'countries.calling_code AS country_calling_code',
            'countries.common_name AS country_common_name',
            'countries.official_name AS country_official_name',
            'countries.endonym AS country_endonym',
            'countries.demonym AS country_demonym'

        );


    }



}
