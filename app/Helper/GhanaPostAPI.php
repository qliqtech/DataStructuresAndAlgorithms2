<?php

namespace App\Helper;

class GhanaPostAPI
{



    public static function VerifyByGPSCode($code){



        $postRequest = array(
            'Action' => 'GetLocation',
            'GPSName' => $code
        );

        $headers = array(
            'Authorization: Basic Q2VydGlmaWNhdGVEZWxpdmVyeUFwcDpVMHR2Y214bGVTNU9VMU5BVG1GMGFXOXVZV3dnVTJWeWRtbGpaU0JUWldOeVpYUmhjbWxoZEE9PQ==',
            'DeviceID: CertificateDeliveryApp',
            'Cookie: ASP.NET_SessionId=npok3jcgkvp2iumcsr1vugx3'
        );


        $cURLConnection = curl_init('https://api.ghanapostgps.com/v2/PublicGPGPSAPI.aspx');
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);

        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        if($apiResponse == null){

            return null;

        }

// $apiResponse - available data from the API request
        $jsonArrayResponse = (Array)json_decode($apiResponse);


        if(!array_key_exists('Table',$jsonArrayResponse)){


            return null;
        }


        return $jsonArrayResponse["Table"];

        //   dd($jsonArrayResponse);

        //   $jsonArr
    }


}
