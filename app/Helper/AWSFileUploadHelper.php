<?php

namespace App\Helper;

use App\Helpers\GenerateRandomCharactersHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AWSFileUploadHelper
{




    private function uploaddocsToS3(Request $request,$location,$key,$userid){



        $base_location = $location;


        // Handle File Upload
        if($request->hasFile($key)) {
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$documentPath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);

            //   $documentPath = $request->file($key)->store($base_location, 's3');

            $randomcharacter = GenerateRandomCharactersHelper::generaterandomAlphabets(5);

            $imageName = $userid. "_".$randomcharacter.".".$request->file($key)->getClientOriginalExtension();

            $storagePath = Storage::disk('s3')->putFileAs($base_location,$request->file($key),$imageName ,'public');

            $objecturl = "https://inpath-logistics-hub-bucket.s3-eu-west-1.amazonaws.com/".$storagePath;

            return $objecturl;

        } else {
            //    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);

            return "";


            //    echo "nofileuploaded";

            //   die();
        }
    }


}
