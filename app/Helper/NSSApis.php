<?php

namespace App\Helper;

use App\ImplementationService\BaseImplemetationService;

class NSSApis
{

    private $baseurl;

    private $nssapibearertoken;

    private $nssapiappsignature;

    private $useragencyapikey;

    public function __construct($useragencyapikey)
    {

        $this->baseurl = env("NSSAPI_BASE_URL","");
        $this->nssapibearertoken = env("NSSAPI_BEARER_TOKEN","");
        $this->nssapiappsignature = env("NSSAPI_APP_SIGNATURE","");

        $this->useragencyapikey = $useragencyapikey; // "ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBjM01pT2lKemQybDBZMmd1Ym5OekxtZHZkaTVuYUNJc0ltRjFaQ0k2SWtaTVFVbFNJRUZIUlU1RFNVVlRJaXdpYVdGMElqb3hOalEzTURJME5qQTBMQ0p1WW1ZaU9qRTJORGN3TWpRMk1EUXNJbVJoZEdFaU9uc2lZMjl1ZEdGamRGOXBaQ0k2SWpVM016QXlOVEE0SWl3aVkyOXVkR0ZqZEY5MGVYQmxhV1FpT2lJeE15SXNJbVZ0WVdsc0lqb2lZV1JxYVhKcGQybHNjMjl1UUdkdFlXbHNMbU52YlNKOWZRLllOSXNadS1NeE9qUFJoLUtOdEM2dzNiNFVCTGNyb0Z5Q1Z4RlBSZ2VuMlk=";

      //  dd($useragencyapikey);
    }



    public  function listdistrictsonNSS(){

        $baseimplementationservice = new BaseImplemetationService();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl. '/v1.0/view/districts?rows=500',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                'ua-api-key: '.$this->useragencyapikey
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];



        if($responsecode!="200"){

         //   echo "Error: ". $response; die();

         //   die();
            $baseimplementationservice->StopProcessAndDisplayMessage(404, $response);

        }



        curl_close($curl);




        return  json_decode($response,true);
    }




    public  function listregionsonNSS(){



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl. '/v1.0/view/regions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                'ua-api-key: '.$this->useragencyapikey
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];



        if($responsecode!="200"){

            echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  json_decode($response,true);
    }





    public function searchcompanies($companyname, $region, $district){


        $curl = curl_init();


        $companyname = urlencode($companyname);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl. "/v1.0/view/companies?search=$companyname&region=$region&district=$district",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                'ua-api-key: '.$this->useragencyapikey
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];



        if($responsecode!="200"){

            echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  json_decode($response,true);

    }

    public function adduseragentaccount($surname,$othername,$email,$phonenumber,$companyid){


        $requestbody = '{
    "surname": "@surname",
    "othername": "@othername",
    "email": "@emailaddress",
    "phone": "@phonenumber",
    "companyid": @companyid
}';

        $requestbody =  str_replace("@surname",$surname,$requestbody);
        $requestbody =  str_replace("@othername",$othername,$requestbody);
        $requestbody =  str_replace("@emailaddress",$email,$requestbody);
        $requestbody =  str_replace("@phonenumber",$phonenumber,$requestbody);
        $requestbody =  str_replace("@companyid",$companyid,$requestbody);

    //    dd($requestbody);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl.'/v1.0/add/user',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$requestbody,
            CURLOPT_HTTPHEADER =>  array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                'ua-api-key: '.$this->useragencyapikey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true) ;
    }



    public function districts($companyname, $region, $district){


        return array(

            0 =>
                array(
                    'id' => 0,
                    'companyname' => 'Company 1',

                ),
            1 =>
                array(

                    'id' => 2,
                    'companyname' => 'Company 2',
                    'region' => 'District',

                ));

    }



    public function verifyuser($emailaddress, $companyid){




        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl."/v1.0/verify/user",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"email":"'.$emailaddress.'",
                                    "companyid":"'.$companyid.'"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                'ua-api-key: '.$this->useragencyapikey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];

        //   dd($response);

        if($responsecode!="200"){

       //     echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  json_decode($response,true);




    }



    public function authenticateuser($emailaddress){




        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl."/v1.0/provide/auth",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"email":"'.$emailaddress.'"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
             //   'ua-api-key: '.$this->useragencyapikey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];

        //   dd($response);

        if($responsecode!="200"){

            //     echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  json_decode($response,true);




    }




    public function getuserdetailsfromNSS($emailaddress){




        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl."/v1.0/verify/user",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"email":"'.$emailaddress.'"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                //   'ua-api-key: '.$this->useragencyapikey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];

        //   dd($response);

        if($responsecode!="200"){

            //     echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  json_decode($response,true);




    }






    public function getcompanyonnssdata($companyid){




        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseurl."/v1.0/agency/profile",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"companyid":"'.$companyid.'"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->nssapibearertoken,
                'x-app-signature: '.$this->nssapiappsignature,
                'ua-api-key: '.$this->useragencyapikey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        $info = curl_getinfo($curl);
        $responsecode = $info["http_code"];

        //   dd($response);

        if($responsecode!="200"){

            //     echo "Error: ". $response; die();

        }



        curl_close($curl);




        return  json_decode($response,true);




    }



}
