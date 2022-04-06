<?php

namespace App\ImplementationService;

use App\CacheOperations\CacheUserRegistrationOps;
use App\DBOperations\UserDBOperations;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService  extends BaseImplemetationService
{




    public function listflairadmins($rowsperpage, $currentpage, $search, $orderby)
    {



        //   dd($orderby);

        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }


        try {

            //companyname,companyid,repname,reprole ,companylocation,companylogo,status,region


         //   if($search == null){

                $users =  DB::table('users')
                    ->select('id',
                        'fullname',
                        'useraccountstatus',
                        'front_portrait_url',
                        'email',

                    )
                    ->Where('fullname','LIKE','%' . $search. '%')
                    ->Where('userroleid','=',UserRoles::FlairAdmin)
                    ->Where('IsDeleted','=',null)



                    ->orderBy("id",$orderby)
                    ->paginate($rowsperpage,['*'],'page',$currentpage);


                return $users->toArray();

         //   }else{

         //       $companies =  DB::table('companies')
         //           ->join('users', 'users.employerid', '=', 'companies.id')
          //          ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
          //          ->select('companies.id AS companyid', 'registeredcompanyname',
           //             'companylogourl',
           //             'jobrolename',
           //             'fullname',
           //             'regionname',
            //            'statusname',
           //             'statusname_flair'
            //        )->where('registeredcompanyname','LIKE','%' . $search. '%')

           //         ->orderBy("companyid",$orderby)
           //         ->paginate($rowsperpage,['*'],'page',$currentpage);



         //       return $companies->toArray();




        //    }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }




    public function listnssadmins($rowsperpage, $currentpage, $search, $orderby)
    {



        //   dd($orderby);

        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }


        try {

            //companyname,companyid,repname,reprole ,companylocation,companylogo,status,region


            //   if($search == null){

            $users =  DB::table('users')
                ->select('id',
                    'fullname',
                    'useraccountstatus',
                    'front_portrait_url',
                    'email',

                )
                ->orWhere('fullname','LIKE','%' . $search. '%')
                ->Where('userroleid','=',UserRoles::NSSAdministrator)
                ->Where('IsDeleted','=',null)


                ->orderBy("id",$orderby)
                ->paginate($rowsperpage,['*'],'page',$currentpage);


            return $users->toArray();

            //   }else{

            //       $companies =  DB::table('companies')
            //           ->join('users', 'users.employerid', '=', 'companies.id')
            //          ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
            //          ->select('companies.id AS companyid', 'registeredcompanyname',
            //             'companylogourl',
            //             'jobrolename',
            //             'fullname',
            //             'regionname',
            //            'statusname',
            //             'statusname_flair'
            //        )->where('registeredcompanyname','LIKE','%' . $search. '%')

            //         ->orderBy("companyid",$orderby)
            //         ->paginate($rowsperpage,['*'],'page',$currentpage);



            //       return $companies->toArray();




            //    }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }



    public function listcandidates($rowsperpage, $currentpage, $search, $orderby)
    {



        //   dd($orderby);

        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }


        try {


            $users =  DB::table('users')
                ->select('id',
                    'fullname',
                    'useraccountstatus',
                    'front_portrait_url',
                    'email',

                    DB::raw('DATE_FORMAT(created_at, "%d %M, %Y") as created_at')
                )
                ->orWhere('fullname','LIKE','%' . $search. '%')
                ->Where('userroleid','=',UserRoles::NSSCandidate)
                ->orderBy("id",$orderby)
                ->paginate($rowsperpage,['*'],'page',$currentpage);




            return $users->toArray();

            //   }else{

            //       $companies =  DB::table('companies')
            //           ->join('users', 'users.employerid', '=', 'companies.id')
            //          ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
            //          ->select('companies.id AS companyid', 'registeredcompanyname',
            //             'companylogourl',
            //             'jobrolename',
            //             'fullname',
            //             'regionname',
            //            'statusname',
            //             'statusname_flair'
            //        )->where('registeredcompanyname','LIKE','%' . $search. '%')

            //         ->orderBy("companyid",$orderby)
            //         ->paginate($rowsperpage,['*'],'page',$currentpage);



            //       return $companies->toArray();




            //    }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }




    public function listcompanyreps($rowsperpage, $currentpage, $search, $orderby)
    {



        //   dd($orderby);

        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }


        try {


            $users =  DB::table('users')
                ->join('companies', 'users.employerid', '=', 'companies.id')
                ->join('userroles', 'users.userroleid', '=', 'userroles.id')
                ->select('users.id AS userid', 'registeredcompanyname',
                    'companylogourl',
                    'jobrolename',
                    'fullname',
                    'email',
                    'userrolename',
                    'users.slug AS userroleslug',
                    'users.userroleid',
                    'userroles.type AS user_role_type',
                    'phonenumber',
                    'useraccountstatus',
                    'regionname',

                    DB::raw('DATE_FORMAT(users.created_at, "%d %M, %Y") as created_at')


                )->where('fullname','LIKE','%' . $search. '%')
                ->whereIn('userroleid',[UserRoles::CompanyAdmin,UserRoles::CompanyRep,])
                ->Where('users.IsDeleted','=',null)

                ->orderBy("userid",$orderby)
                ->paginate($rowsperpage,['*'],'page',$currentpage);




            return $users->toArray();

            //   }else{

            //       $companies =  DB::table('companies')
            //           ->join('users', 'users.employerid', '=', 'companies.id')
            //          ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
            //          ->select('companies.id AS companyid', 'registeredcompanyname',
            //             'companylogourl',
            //             'jobrolename',
            //             'fullname',
            //             'regionname',
            //            'statusname',
            //             'statusname_flair'
            //        )->where('registeredcompanyname','LIKE','%' . $search. '%')

            //         ->orderBy("companyid",$orderby)
            //         ->paginate($rowsperpage,['*'],'page',$currentpage);



            //       return $companies->toArray();




            //    }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }




    public function listmycompanyreps($rowsperpage, $currentpage, $search, $orderby,$companyid)
    {



        //   dd($orderby);

        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }


        try {


            $users =  DB::table('users')
                ->join('companies', 'users.employerid', '=', 'companies.id')
                ->join('userroles', 'users.userroleid', '=', 'userroles.id')
                ->select('users.id AS userid', 'registeredcompanyname',
                    'companylogourl',
                    'jobrolename',
                    'fullname',
                    'email',
                    'userrolename',
                    'users.slug AS userroleslug',
                    'users.userroleid',
                    'userroles.type AS user_role_type',

                    'phonenumber',
                    'useraccountstatus',
                    'regionname',

                    DB::raw('DATE_FORMAT(users.created_at, "%d %M, %Y") as created_at')


                )->where('fullname','LIKE','%' . $search. '%')
                ->whereIn('userroleid',[UserRoles::CompanyAdmin,UserRoles::CompanyRep,])
                ->where('employerid','=',$companyid)
                ->Where('users.IsDeleted','=',null)

                ->orderBy("userid",$orderby)
                ->paginate($rowsperpage,['*'],'page',$currentpage);





            return $users->toArray();

            //   }else{

            //       $companies =  DB::table('companies')
            //           ->join('users', 'users.employerid', '=', 'companies.id')
            //          ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
            //          ->select('companies.id AS companyid', 'registeredcompanyname',
            //             'companylogourl',
            //             'jobrolename',
            //             'fullname',
            //             'regionname',
            //            'statusname',
            //             'statusname_flair'
            //        )->where('registeredcompanyname','LIKE','%' . $search. '%')

            //         ->orderBy("companyid",$orderby)
            //         ->paginate($rowsperpage,['*'],'page',$currentpage);



            //       return $companies->toArray();




            //    }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }




    public function deactivateuser($params){



        $selecteduser = new User();

        $userdboperations = new UserDBOperations($selecteduser);

        $responsearray = array();



        try {

            if ($params == null) {

                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "User Deactivation";


            $selecteduser = User::find($params["selecteduserid"]);

            $selecteduser->useraccountstatus = "inactive";

            $selecteduser->DeactivatedOn = now();

            $selecteduser->IsActive = false;



            $selecteduser->updated_by = $params["userid"];

            $selecteduser->save();



        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );


            //   echo $ex->getMessage();die();


            $this::StopProcessAndDisplayMessage(500,$ex->getMessage());
            $params['activityname'] = "User Deactivation";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //

         $this::responseHelper($responsearray);

        return array('responsemessage'=>'User Deactivated Successfully');
    }



    public function activateuser($params){



        $selecteduser = new User();

        $userdboperations = new UserDBOperations($selecteduser);

        $responsearray = array();



        try {

            if ($params == null) {

                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "User Deactivation";


            $selecteduser = User::find($params["selecteduserid"]);

            $selecteduser->useraccountstatus = "active";

            $selecteduser->DeactivatedOn = null;

            $selecteduser->IsActive = true;



            $selecteduser->updated_by = $params["userid"];

            $selecteduser->save();



        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage(500,$ex->getMessage());
            $params['activityname'] = "User Activation";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //

        $this::responseHelper($responsearray);

        return array('responsemessage'=>'User Activated Successfully');
    }



    public function deleteuser($params){



        $selecteduser = new User();

        $userdboperations = new UserDBOperations($selecteduser);

        $responsearray = array();



        try {

            if ($params == null) {

                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "User Deactivation";


            $selecteduser = User::find($params["selecteduserid"]);

            $selecteduser->useraccountstatus = "deleted";

            $selecteduser->IsDeletedOn = now();

            $selecteduser->IsDeleted = true;

            $selecteduser->DeletedBy = $params["userid"];


            $selecteduser->save();



        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage(500,$ex->getMessage());
            $params['activityname'] = "User Activation";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //

        $this::responseHelper($responsearray);

        return array('responsemessage'=>'User Deleted Successfully');
    }




    public function listuserroles($rowsperpage, $currentpage, $search, $orderby)
    {



        //   dd($orderby);

        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }


        try {

            //companyname,companyid,repname,reprole ,companylocation,companylogo,status,region


            //   if($search == null){

            $users =  DB::table('userroles')
                ->select('userroles.id AS userroleid',
                    'userrolename',
                    'status',
                    'description',
                    DB::raw("count(users.id) as numberofusers")
                )->leftJoin('users', 'users.userroleid', '=', 'userroles.id')

                ->orWhere('userrolename','LIKE','%' . $search. '%')
                ->groupBy('userroles.id')

                ->orderBy("userroles.id",$orderby)
                ->paginate($rowsperpage,['*'],'page',$currentpage);


            return $users->toArray();

            //   }else{

            //       $companies =  DB::table('companies')
            //           ->join('users', 'users.employerid', '=', 'companies.id')
            //          ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
            //          ->select('companies.id AS companyid', 'registeredcompanyname',
            //             'companylogourl',
            //             'jobrolename',
            //             'fullname',
            //             'regionname',
            //            'statusname',
            //             'statusname_flair'
            //        )->where('registeredcompanyname','LIKE','%' . $search. '%')

            //         ->orderBy("companyid",$orderby)
            //         ->paginate($rowsperpage,['*'],'page',$currentpage);



            //       return $companies->toArray();




            //    }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }





}
