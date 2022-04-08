<?php

namespace App\Http\Controllers;


use App\Enums\ApiResponseCodesKeysAndMessages;
use App\Enums\UserRoles;
use App\ImplementationService\UserService;
use App\Models\Userrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UserRoleController extends Controller
{

    public function createrole(Request $request){



        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $request->json()->all();



        if($request->user()->userroleid != UserRoles::FlairAdmin){


        return response(array('responsemessage'=>'Access denied. Flair Admins only'),401);

    }

        $validator = Validator::make($datafromrequest, [

            'userrolename' => 'required|string',
            'description' => 'required|string',
            'permissions' => 'required',
            'realmid' => 'required|int',
        ]);
        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }


        $allkeys = $request->all();

        $slug = str_replace(" ","_",$allkeys['userrolename']) ;

        $slug = strtolower($slug);

        $allkeys['type'] = 'public';

        $allkeys['slug'] = $slug;

        $allkeys['status'] = 'active';

        $allkeys['type'] = 'public';

        $allkeys['permissions'] = json_encode($allkeys['permissions']);

        $allkeys['is_system_generated'] = false;


        Userrole::create($allkeys);



        return response(array('responsemessage'=>'User role added successfully'),200);


    }



    public function updateuserrole(Request $request){



        $request->request->add($this->GetUserAgent($request));

        $datafromrequest = $request->json()->all();



        if($request->user()->userroleid != UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Access denied. Flair Admins only'),401);

        }

        $validator = Validator::make($datafromrequest, [

            'userrolename' => 'required|string',
            'description' => 'required|string',
            'permissions' => 'required',
            'userroleid' => 'required|int',
        ]);
        if ($validator->fails())
        {

            $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey=>ApiResponseCodesKeysAndMessages::SuccessCode,
                ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey=>'Validation Errors',
                'errors'=>$validator->errors()->toArray()


            );


            return response($responsevalues,400);


        }


        $allkeys = $request->all();

        $slug = str_replace(" ","_",$allkeys['userrolename']) ;

        $slug = strtolower($slug);

      //  $allkeys['type'] = 'public';

        $allkeys['slug'] = $slug;

     //   $allkeys['status'] = 'active';

     //   $allkeys['type'] = 'public';

        $allkeys['permissions'] = json_encode($allkeys['permissions']);

     //   $allkeys['is_system_generated'] = false;


        $userroledetails = Userrole::where('id',$allkeys["userroleid"])->first();

        if($userroledetails == null){

            return response(array('responsemessage'=>'User role cannot be found'),404);

        }


        if($userroledetails->is_system_generated == true){


            return response(array('responsemessage'=>'User role cannot be updated. It is system generated '),401);

        }

        Userrole::where('id',$allkeys["userroleid"])->
        update(['userrolename' => $allkeys['userrolename'],
            'slug' => $allkeys['slug'],
            'description' => $allkeys['description'],
            'permissions' => $allkeys['permissions'],
            ]);



        return response(array('responsemessage'=>'User role updated successfully'),200);


    }


    public function userroledetail(Request $request){


        if($request->user()->userroleid != UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Access denied. Flair Admins only'),401);

        }

       $userrole =  Userrole::find($request->userroleid);


        if($userrole == null){


            response(array('responsemessage'=>'User role does not exist',),404);
        }

        $userrealm = DB::table('realms')->find($userrole->realmid);

        if($userrealm == null){


            response(array('responsemessage'=>'User realm does not exist',),404);
        }

      //  dd($userrole->permissions);

        $permissions = json_decode($userrole->permissions,true);

        $dataforpermisions = array();

        if($permissions != null){

            $dataforpermisions = Db::table('permissions')->whereIn('slug', $permissions)->get();

        }



        $userrole["realm"] = $userrealm;

        return response(array('responsemessage'=>'User roles',
            'userroledetails'=>$userrole,
            'permissions'=>$dataforpermisions
        ),200);







    }


    public function listpermissions(Request $request){




        if($request->user()->userroleid != UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Access denied. Flair Admins only'),401);

        }


        $dataforpermisions =  null ;//  Db::table('permissions')->get();


        if($request->category == null){

         //   $dataforpermisions =  Db::table('permissions')->get();


        }else{

       //     $dataforpermisions = Db::table('permissions')->where('realmid','=',$request->realmid)->get();


        }

        $dataforpermisions = Db::table('permissions')->where('realmid','=',$request->realmid)->get();


        return response(array('responsemessage'=>'User Permissions',

            'permissions'=>$dataforpermisions
        ),200);




    }




    public function realmslist(Request $request){


        return DB::table('realms')->whereIn('id',[1,2,3])->get();

    }



    public function listuserroles(Request $request){


        if($request->user()->userroleid != UserRoles::FlairAdmin){


            return response(array('responsemessage'=>'Access denied. Flair Admins only'),401);

        }

        if($request->realmid == null){


            return response(array('responsemessage'=>'realmid is mandatory'),400);

        }


        $usermanagementservice = new UserService();


        return $responsevalues = array(ApiResponseCodesKeysAndMessages::ResponseCodeKey => ApiResponseCodesKeysAndMessages::SuccessCode,
            ApiResponseCodesKeysAndMessages::ResponseMessageCodeKey => 'User roles List',
            'details' => $usermanagementservice->listuserroles($request->rowsperpage,$request->page,$request->search,$request->order,$request->realmid));

    }



    public function deactivateuserrole(Request $request){

        if($request->userroleid == null){


            return response(array('responsemessage'=>'userroleid is required'),401);

        }


        if($request->user()->userroleid == UserRoles::FlairAdmin){


            $userroleid = $request->userroleid;

            $userroledetails = Userrole::find($userroleid);

            if($userroledetails == null){


                return response(array('responsemessage'=>'User role does not exist'),404);
            }

            if($userroledetails->is_system_generated == true){


                return response(array('responsemessage'=>'User role cannot be disabled. System Generated'),401);

            }


            $userroledetails->status = "inactive";

            $userroledetails->save();

            return response(array('responsemessage'=>'User role Deactivated'),200);

        }



    }



    public function activateuserrole(Request $request){

        if($request->userroleid == null){


            return response(array('responsemessage'=>'userroleid is required'),401);

        }


        if($request->user()->userroleid == UserRoles::FlairAdmin){


            $userroleid = $request->userroleid;

            $userroledetails = Userrole::find($userroleid);

            if($userroledetails == null){


                return response(array('responsemessage'=>'User role does not exist'),404);
            }

            if($userroledetails->is_system_generated == true){


                return response(array('responsemessage'=>'User role cannot be enabled. System Generated'),401);

            }


            $userroledetails->status = "active";

            $userroledetails->save();

            return response(array('responsemessage'=>'User role Activated'),200);

        }



    }

    public function permissioncategorylist(Request $request)
    {


        if ($request->user()->userroleid == UserRoles::FlairAdmin) {


            $permissioncategory = DB::table('permissions')->select(['category', 'categoryslug'])->groupBy('categoryslug')->get();

            return $permissioncategory;
        }

        return response(array('responsemessage'=>'Unauthorized access'),401);

    }


}
