<?php

namespace App\Helper;

class FlairInternalAPIs
{


    private $baseurl;

    private $bearertoken;

    private $useragencyapikey;

    public function __construct($bearertoken)
    {

        $this->baseurl = "https://postings-and-requests.api.myflair.africa/api";
        $this->bearertoken = $bearertoken;


        //  dd($useragencyapikey);
    }


    public function getNSPDetailsbyNSSnumber($nssnumber){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl. '/nsprequests/search_single_nsp_by_nss_number_unauth?nssnumber='.$nssnumber,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->bearertoken),
        ));

        $response = curl_exec($curl);

        curl_close($curl);



        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];


        if($responsecode != "200"){


            return null;

        }


        return json_decode($response,true);

    }
}
