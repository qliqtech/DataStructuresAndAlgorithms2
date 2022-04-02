<?php

namespace App\Http\Controllers;


use App\Helper\GenerateRandomCharactersHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{



    public function uploadfile_private(Request $request){



        $base_location = $request->directory;

        $fieldname = $request->fieldname;


        if($base_location == null){


            return response(array('responsecode'=>'400',
                'responsemessage'=>'directory is required'
            ),400);


        }

        // Handle File Upload
        if($request->hasFile('fileupload')) {

          //  $randomcharacter = GenerateRandomCharactersHelper::generaterandomAlphabets(5);

            $imageName = $request->file('fileupload')->getClientOriginalName();

          //  dd($imageName);



            $storagePath = Storage::disk('s3')->putFileAs("".$base_location,$request->file('fileupload'),$imageName ,'private');


         //   dd($storagePath);
            $objecturl = "https://s3.amazonaws.com/flair.africa/".$storagePath;



            return response(array('responsemessage'=>'fileuploaded successfully',
                'fieldname'=>$fieldname,
                'fileurl'=>$objecturl
            ));

        } else {
            //    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);

            return response(array('responsecode'=>'400',
                                'No file uploaded'=>''
                ),400);


        }
    }




    public function uploadfile_public(Request $request){



        $base_location = $request->directory;

        $fieldname = $request->fieldname;


        if($base_location == null){


            return response(array('responsecode'=>'400',
                'responsemessage'=>'directory is required'
            ),400);


        }

        // Handle File Upload
        if($request->hasFile('fileupload')) {

            //  $randomcharacter = GenerateRandomCharactersHelper::generaterandomAlphabets(5);

            $imageName = $request->file('fileupload')->getClientOriginalName();

            $randomcharacter = GenerateRandomCharactersHelper::generaterandomAlphabets(4);

            $imageName = $randomcharacter."_". $imageName;

            //  dd($imageName);



            $storagePath = Storage::disk('flairpublicbucket')->putFileAs("".$base_location,$request->file('fileupload'),$imageName ,'public');


            //   dd($storagePath);
            $objecturl = "https://s3.amazonaws.com/flair.africa.pub/".$storagePath;



            return response(array('responsemessage'=>'fileuploaded successfully',
                                  'fileurl'=>$objecturl,
                                   'fieldname'=>$fieldname
                ));


        } else {
            //    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);

            return response(array('responsecode'=>'400',
                'No file uploaded'=>''
            ),400);


        }
    }




    public function downloadimage(){


        $s3 = Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+10 minutes";

        $command = $client->getCommand('GetObject', [
            'Bucket' => 'flair.africa',
            'Key'    => "file/in/s3/bucket"
        ]);

        $request = $client->createPresignedRequest($command, $expiry);

        return (string) $request->getUri();



    }

    public function deletefile(Request $request){

        $imagename = $request->fileurl;


        if($imagename == null){


            return response(array('responsecode'=>'400',
                'responsemessage'=>'fileurl is required'
            ),400);

        }

        if(str_contains($imagename,"/flair.africa.pub/")) {

            $imagename = str_replace("https://s3.amazonaws.com/flair.africa.pub/", "", $imagename);


          //  dd($imagename);

            Storage::disk('flairpublicbucket')->delete( $imagename);


            return response(array('responsemessage'=>'image deleted successfully'));

        }

        if(str_contains($imagename,"/flair.africa/")) {

            $imagename = str_replace("https://s3.amazonaws.com/flair.africa.pub/", "", $imagename);


          //  dd($imagename);

            Storage::disk('s3')->delete($imagename);


            return response(array('responsemessage'=>'image deleted successfully'));


        }

        return response(array('responsemessage'=>'Wrong URL'),500);


    }


    public function downloadprivatefile(Request $request) {
    //    $document = Document::find($id);
    try {

        $s3 = Storage::disk('s3');

      //  dd($s3);

        $name = $request->fileurl; // substr($document->link, strrpos($document->link, '/') + 1);

        $name = str_replace("https://s3.amazonaws.com/flair.africa","",$name);

    //    dd($name);
        //l

        $file = $s3->get($name);

        $extension = pathinfo($request->fileurl, PATHINFO_EXTENSION); // get ext from path


        return response($file)
            ->header('Content-Type', 'application/'.$extension)
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Disposition', "attachment; filename={$name}")
            ->header('Filename', $name);

    }catch(\Exception $exception){

        return response(array('responsemessage'=>'Error:'.$exception->getMessage()),500);
    }



    }

}
