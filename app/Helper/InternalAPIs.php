<?php

namespace App\Helper;

class InternalAPIs
{


    private $usertoken;

    private $baseurl;


    public function __construct($usertoken)
    {

        //   $this->baseurl = env("NSSAPI_BASE_URL","");
        //  $this->nssapibearertoken = env("NSSAPI_BEARER_TOKEN","");
        $this->usertoken = $usertoken;

        //    dd($useragencyapikey);

        //    $this->useragencyapikey = $useragencyapikey;//  "ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBjM01pT2lKemQybDBZMmd1Ym5OekxtZHZkaTVuYUNJc0ltRjFaQ0k2SWtaTVFVbFNJRUZIUlU1RFNVVlRJaXdpYVdGMElqb3hOalEyTURnMU1EVTVMQ0p1WW1ZaU9qRTJORFl3T0RVd05Ua3NJbVJoZEdFaU9uc2lZMjl1ZEdGamRGOXBaQ0k2SWpVM016QXlOVEE0SWl3aVpXMWhhV3dpT2lKaFpHcHBjbWwzYVd4emIyNUFaMjFoYVd3dVkyOXRJbjE5LkhJLXJtWV9fSDRGbVFncW82dVVJYmViVEI4QjdqdzllbWhENWJaSFJaSFE=";

    }


    public  function getuserdetails($token){

        $baseurl = env('ONBOARDING_BASE_URL');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl.'/api/getuserdetailsraw',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token,

            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];



        if($responsecode!="200"){

          //  echo "Error: ". $response; die();

            return null;
        }



        curl_close($curl);




        return  json_decode($response,true);
    }




    public  function getcompanyinfo($companyid){

        //    dd($this->usertoken);

        $baseurl = env('ONBOARDING_BASE_URL');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl.'/api/companyonboarding/view_company_detailsby_id?companyid='.$companyid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->usertoken,

            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];



        if($responsecode!="200"){

            echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  (object)json_decode($response,true);
    }




}
