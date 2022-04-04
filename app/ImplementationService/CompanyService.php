<?php

namespace App\ImplementationService;

use App\CacheOperations\CacheCompanyRegistrationOps;
use App\DBOperations\BranchesDBOperations;
use App\DBOperations\CompanyApprovalStatusDbOps;
use App\DBOperations\CompanyDBOperations;
use App\DBOperations\CompanyImagesDBOperations;
use App\DBOperations\CompanyIndustryDBOperations;
use App\DBOperations\JobRolesDBOperations;
use App\DBOperations\RegionDBOperations;
use App\DBOperations\UserDBOperations;
use App\Enums\CompanyApprovalRequestEnumns;
use App\Enums\CompanyTypeEnums;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\NSSApis;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Companyimage;
use App\Models\CompanyIndustry;
use App\Models\Companynssapprovalstatus;
use App\Models\JobRole;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyService extends BaseImplemetationService
{




    public function listindustries(){

        $industry = new CompanyIndustry();

        $industrydbops = new CompanyIndustryDBOperations($industry);

       return $industrydbops->listall();

}


    public function listregions(){

        $region = new Region();

        $regiondbops = new RegionDBOperations($region);

        return $regiondbops->listall();

    }


    public function listjobRoles(){

        $job = new JobRole();

        $jobdbops = new JobRolesDBOperations($job);

        return $jobdbops->listall();

    }


    public function registercompany($params) : array
    {

        $company = new Company();

        $user = new User();

        $regiondbops = new RegionDBOperations(new Region());


        $companydboperations = new CompanyDBOperations($company);

        $companyindustrydbops = new CompanyIndustryDBOperations(new CompanyIndustry());


        $userdboperations = new UserDBOperations($user);


        $userhascompany = $userdboperations->find($params["userid"]);

        if($userhascompany!=null){

            if($userhascompany->employerid!=null){

                $this::StopProcessAndDisplayMessage(401,"user already has a company");

            }


        }


        $responsearray = array();



        $params["companydetails"]['IsActive'] =  true;


        $params["companydetails"]['IsApproved'] =  true;


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Company Creation";


            $params["companydetails"]['created_by'] = $params['userid'];


            $params["companydetails"]['industryname'] = $companyindustrydbops->find($params["companydetails"]['industryid'])->industryname;

            $params["companydetails"]['regionname'] = $regiondbops->find($params["companydetails"]['regionid'])->regionname;

            $params["companydetails"]['flairrequeststatus'] = CompanyApprovalRequestEnumns::Pending;


            $companydetails = $companydboperations->create($params["companydetails"]);


            $userdboperations->updateById($params["userid"], array('employerid' => $companydetails->id,
                'userroleid' => UserRoles::CompanyAdmin,
                'onboardingcompanystate' => 'complete'
            ));

            $cachecompanyregistrationdetails = new CacheCompanyRegistrationOps();


            $cachecompanyregistrationdetails->cachecompanyregistrationdetails($companydetails->id, $params["companydetails"]);

            //update company cached details with this info;

            $companydboperations->updateById($companydetails->id, array('companydetailscache' => json_encode($params["companydetails"])));


            if (array_key_exists('imageurls',$params["companydetails"])) {




            foreach ($params["companydetails"]["imageurls"] as $imageurl) {


                $this::addimages($imageurl, $params["userid"], $companydetails->id);




            }

        }

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                     'companyinfo' => $this->checkcompanystatus($companydetails->id)
            );
            //   return $this::responseHelper($responsearray)[0];



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Company Creation";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage(500,$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);





        return $this::responseHelper($responsearray)[0];
    }




    public function completecompanyregistration($params) : array
    {

        $company = new Company();

        $user = new User();



        $companydboperations = new CompanyDBOperations($company);

        $companyindustrydbops = new CompanyIndustryDBOperations(new CompanyIndustry());


        $userdboperations = new UserDBOperations($user);


        //  dd($params["userid"]);

        $userhascompany = $userdboperations->find($params["userid"]);

        if($userhascompany!=null){

            if($userhascompany->employerid==null){

                    $this::StopProcessAndDisplayMessage(401,"user has not signed up for company");

            }


        }


        $responsearray = array();

    //    $params =  array_add($params,'IsActive',true);

     //   $params =  array_add($params,'IsApproved',true);


     //   $params =  array_add($params,'IsDeleted',false);



        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Company Creation";


            $params["companydetails"]['created_by'] = $params['userid'];

            $params["companydetails"]['industryname'] = $companyindustrydbops->find($params["companydetails"]['industryid'])->industryname;

            $companydetails = $companydboperations->find($userhascompany->employerid);


            if($companydetails->IsApproved != true){

                $this::StopProcessAndDisplayMessage(401,"user account is not approved");

            }





            foreach ($params["companydetails"]["imageurls"] as $imageurl){


                $this::addimages($imageurl,$params["userid"],$companydetails->id);


            }

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                //     InAppResponsTypes::responsemessagekey => $token
            );
            //   return $this::responseHelper($responsearray)[0];

            $cachecompanyregistrationdetails = new CacheCompanyRegistrationOps();

            $companydetails = $companydboperations->find($userhascompany->employerid);


            $cachecompanyregistrationdetails->cachecompanyregistrationdetails($companydetails->id, $params["companydetails"]);

         //   $cachecompanyregistrationdetails->cachecompanybranchesdetails($companydetails->id, $branchestocache);


        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Company Creation";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);





        return $this::responseHelper($responsearray)[0];
    }






    public function submitcompanyrequest($params) : array
    {



        $company = new Company();

        $user = new User();

        $companydboperations = new CompanyDBOperations($company);

        $userdboperations = new UserDBOperations($user);



        $userhascompany = $userdboperations->find($params["userid"]);

        if($userhascompany!=null){

            if($userhascompany->employerid==null){

                $this::StopProcessAndDisplayMessage(401,"user has not signed up for company");

            }


        }



        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Submitting Company Request";

            $companydetails = $companydboperations->find($userhascompany->employerid);

            if($companydetails->IsApproved != true){

             //   $this::StopProcessAndDisplayMessage(401,"user account is not approved");

            }



            //verify account
            //if NSS endpoint approves details change isVerifiedOnNSS = true
            // update company column proposedcompanycompanyid with selected companyid;
            //


            $nssapi = new NSSApis("");

           $verificationresult =  $nssapi->verifyuser($params["email"],$params["companyid"]);


       //    dd($verificationresult);

       //    dd($verificationresult);

      //      $userdboperations->updateById()

            $userdboperations->updateById($userhascompany->id,array('isverifiedasuseragent'=>$verificationresult['status']));



            $params["requestapprovalstatus"] =  CompanyApprovalRequestEnumns::Pending;

            $params["authorizationletterurl"] =  $params["authorizationletter_url"];

            $params["requestedcompanyid"] =  $params["companyid"];

            $params["requestsenton"] =  now();

            $params["requestsentby"] =  $params['userid'];


            $companydboperations->updateById($companydetails->id,$params);



        }catch (\Exception $ex){

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Submitting Company Request";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


      //  dd($responsearray);

        return $this::responseHelper($responsearray);
    }





    public function viewcompanydetials($params) : array
    {

        $company = new Company();

        $user = new User();

        $companydetails = null;

        $userdboperations = new UserDBOperations($user);

        $companycacheoperations = new CacheCompanyRegistrationOps($user);


        $userhascompany = $userdboperations->find($params["userid"]);


        if($userhascompany!=null){

            if($userhascompany->employerid==null){

                $this::StopProcessAndDisplayMessage(401,"user does not have company");

            }


        }


        $responsearray = array();



        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }



            $params['activityname'] = "View company details";


            $params["companydetails"]['created_by'] = $params['userid'];


           $companydetails = json_decode($companycacheoperations->getcompanydetailsfromcache($userhascompany->employerid),true) ;


            if(array_key_exists('companytype',$companydetails)){

                $companydetails['companytype'] = $this::Companytype($companydetails['companytype']);


            }

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                //     InAppResponsTypes::responsemessagekey => $token
            );

        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "View Company Details";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);





        return $companydetails;
    }





    public function listcompaniesfornssapproval($rowsperpage, $currentpage, $search, $orderby)
    {

        $company = new Company();

        $user = new User();



        if($rowsperpage == null){

            $rowsperpage = 15;
        }

        if($currentpage == null){

            $currentpage = 1;
        }

        if($orderby == null){

            $orderby = "desc";
        }

     //   dd($rowsperpage);


        $responsearray = array();

        try {

          //companyname,companyid,repname,reprole ,companylocation,companylogo,status,region


            if($search == null){

                $companies =  DB::table('companies')
                    ->join('users', 'users.employerid', '=', 'companies.id')
                    ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.requestapprovalstatus')
                    ->select('companies.id AS companyid', 'registeredcompanyname',
                        'companylogourl',
                        'jobrolename',
                        'fullname',
                        'regionname',
                        'statusname',
                         'statusname_nss'
                    )->orderBy("companyid",$orderby)
                    ->paginate($rowsperpage,['*'],'page',$currentpage);





                return $companies->toArray();

            }else{


                $companies =  DB::table('companies')
                    ->join('users', 'users.employerid', '=', 'companies.id')
                    ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.requestapprovalstatus')
                    ->select('companies.id AS companyid', 'registeredcompanyname',
                        'companylogourl',
                        'jobrolename',
                        'fullname',
                        'regionname',
                        'statusname',
                        'statusname_nss'
                    )->where('registeredcompanyname','LIKE','%' . $search. '%')

                    ->orderBy("companyid",$orderby)
                    ->paginate($rowsperpage,['*'],'page',$currentpage);



                return $companies->toArray();




            }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




       // return $companydetails;
    }










    public function listcompaniesforflairapproval($rowsperpage, $currentpage, $search, $orderby)
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

        //   dd($rowsperpage);


        $responsearray = array();

        try {

            //companyname,companyid,repname,reprole ,companylocation,companylogo,status,region


            if($search == null){

                $companies =  DB::table('companies')
                    ->join('users', 'users.employerid', '=', 'companies.id')
                    ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
                    ->select('companies.id AS companyid', 'registeredcompanyname',
                        'companylogourl',
                        'jobrolename',
                        'fullname',
                        'regionname',
                        'statusname',
                        'statusname_flair'
                    )->orderBy("companyid",$orderby)
                    ->paginate($rowsperpage,['*'],'page',$currentpage);


                return $companies->toArray();

            }else{

                $companies =  DB::table('companies')
                    ->join('users', 'users.employerid', '=', 'companies.id')
                    ->join('Companynssapprovalstatus', 'Companynssapprovalstatus.id', '=', 'companies.flairrequeststatus')
                    ->select('companies.id AS companyid', 'registeredcompanyname',
                        'companylogourl',
                        'jobrolename',
                        'fullname',
                        'regionname',
                        'statusname',
                        'statusname_flair'
                    )->where('registeredcompanyname','LIKE','%' . $search. '%')

                    ->orderBy("companyid",$orderby)
                    ->paginate($rowsperpage,['*'],'page',$currentpage);



                return $companies->toArray();




            }



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );



            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }




        // return $companydetails;
    }



    public function viewcompanydetialsforapproval($params) : array
    {

       // if()

        $companydbops = new CompanyDBOperations(new Company());

        $user = new User();

        $companydetails = null;

        $userdboperations = new UserDBOperations($user);

        $companyindustrydbops = new CompanyIndustryDBOperations(new CompanyIndustry());



        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }



            $params['activityname'] = "View company details";


            $params['created_by'] = $params['userid'];


            $companydetails = $companydbops->find($params["companyid"]);  // json_decode($companycacheoperations->getcompanydetailsfromcache($userhascompany->employerid),true) ;


            $currentuser = $userdboperations->find($params["userid"]);

            $previous = null;

            $next = null;

            if($currentuser->userroleid == UserRoles::FlairAdmin){

                $previous = Company::where('id', '<', $params["companyid"])->where('flairrequeststatus',CompanyApprovalRequestEnumns::Pending)->max('id');

                $numberofcompaniespending = Company::where('flairrequeststatus',CompanyApprovalRequestEnumns::Pending)->count();


                // get next user id
                $next = Company::where('id', '>', $params["companyid"])->where('flairrequeststatus',CompanyApprovalRequestEnumns::Pending)->min('id');
            }


            if($currentuser->userroleid == UserRoles::NSSAdministrator){

                $previous = Company::where('id', '<', $params["companyid"])->where('requestapprovalstatus',null)->max('id');

                $numberofcompaniespending = Company::where('requestapprovalstatus',null)->count();


                // get next user id
                $next = Company::where('id', '>', $params["companyid"])->where('requestapprovalstatus',null)->min('id');
            }

            $pagedetails = array('previous'=>$previous,
                'numberpending'=>$numberofcompaniespending,
                'next'=>$next,
                );


           // dd();

            if($companydetails == null){

                $this::StopProcessAndDisplayMessage(401,"company is does not exist");


            }


            if($companydetails->IsDeleted == true){


                $this::StopProcessAndDisplayMessage(401,"company is deleted");


            }
            //    dd($companydetails->requestsentby);

            $userhascompany = null;

          //  dd($currentuser->userroleid);

            if($currentuser->userroleid == UserRoles::FlairAdmin){

                $userhascompany = $userdboperations->find($companydetails->created_by);


            //    dd()
            }

            if($currentuser->userroleid == UserRoles::NSSAdministrator){

                $userhascompany = $userdboperations->find($companydetails->requestsentby);

            }



            if($userhascompany!=null){

                if($userhascompany->employerid == null){

                    $this::StopProcessAndDisplayMessage(401,"user does not have company");

                }

            }

         //   dd($userhascompany);

            $jobdbops = new JobRolesDBOperations(new Jobrole());

            $jobdetails = $jobdbops->find($userhascompany->jobroleid);



            if($jobdetails == null){


             //   $this::StopProcessAndDisplayMessage(401,"job role id cannot be found");

            }



            $companydetails['companytype'] = $this::Companytype($companydetails['companytype']);



            $companyimagedboperations = new CompanyImagesDBOperations(new Companyimage());


            $images = $companyimagedboperations->listall()->where('companyid',$companydetails->id);

            $imagestoarray = [];

            foreach ($images as $image){


                $imagestoarray[] =   $image->imageurl;

            }

         //   dd($imagestoarray);



            $regiondbops = new RegionDBOperations(new Region());


         //   dd($companydetails['regionid']);

            $region = $regiondbops->find($companydetails['regionid']);

            $region = $regiondbops->find($companydetails['regionid']);


            $nssapi = new NSSApis("");
                /////
            ///
            $companystatusdbops = new CompanyApprovalStatusDbOps(new Companynssapprovalstatus());

            $companyapprovalstatus = DB::table('Companynssapprovalstatus')->select(['id','statusname','statusname_flair'])->find($companydetails["flairrequeststatus"]);


            $nssapprovalstatus =  DB::table('Companynssapprovalstatus')->select(['id','statusname','statusname_nss'])-> find($companydetails["requestapprovalstatus"]);


         //   dd($nssapprovalstatus);

            $nsscompanydetails = $nssapi ->getcompanyonnssdata($companydetails["requestedcompanyid"]);


            $companyindustry = $companyindustrydbops ->find($companydetails->industryid);

            if(array_key_exists('profile',$nsscompanydetails)){

                $companyname = $nsscompanydetails["company"];

                $nsscompanydetails = $nsscompanydetails['profile'];

                $nsscompanydetails["company_name"] = $companyname;

            }

            $companydetails = array(
                'companydetails'=>array(
                'companyname'=>$companydetails["registeredcompanyname"],
                'companylogourl'=>$companydetails["companylogourl"],
                'yearfounded'=>$companydetails["yearfounded"],

                    'nssapprovalstatus'=>$nssapprovalstatus,
                    'companytype'=> $this::Companytype($companydetails['companytype']),
                'website'=>$companydetails['website'],
                    'numberofemployeesrange'=>$companydetails['numberofemployeesrange'],
                    'region'=>$region,
                    'industry'=>$companyindustry,
             //   'approvalstatus'=>$this::ApprovalStatus($companydetails['requestapprovalstatus']),
                'about'=>$companydetails['aboutcompany'],
                    'companyapprovalstatus'=>$companyapprovalstatus,
                'address'=>$companydetails['address'],
                'biotagline'=>$companydetails['biotagline'],
                    'ghanapostaddress'=>$companydetails['ghanapostaddress'],
                    'authorizationletter_url'=>$companydetails['authorizationletterurl'],
                        'pagedetails'=>$pagedetails,
                    'imageurls'=>$imagestoarray,
            ),
                'nsscompanydetails'=>$nsscompanydetails
                ,
                'companyrepdetails'=>array(
                    'fullname'=>$userhascompany->fullname,
                    'jobrole'=> $jobdetails,
                    'email'=>$userhascompany->email,
                    'phonenumber'=>$userhascompany->phonenumber,
                     'isverified' =>$userhascompany->isverifiedasuseragent
                )


            );

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                //     InAppResponsTypes::responsemessagekey => $token
            );

        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "View Company Details";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);





        return $companydetails;
    }




    public function viewcompanybranches($params) : array
    {

        $company = new Company();

        $user = new User();

        $companydboperations = new CompanyDBOperations($company);

        $userdboperations = new UserDBOperations($user);

        $companycacheoperations = new CacheCompanyRegistrationOps($user);



        //  dd($params["userid"]);

        $userhascompany = $userdboperations->find($params["userid"]);

        if($userhascompany!=null){

            if($userhascompany->employerid==null){

                $this::StopProcessAndDisplayMessage(401,"user does not have company");

            }


        }


        $responsearray = array();





        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }



            $params['activityname'] = "View company details";


            $params["companydetails"]['created_by'] = $params['userid'];


            $branchdetails = json_decode($companycacheoperations->getcompanybranchesfromcache($userhascompany->employerid),true) ;

          //  $companydetails['companytype'] = $this::Companytype($companydetails['companytype']);



            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                //     InAppResponsTypes::responsemessagekey => $token
            );
            //   return $this::responseHelper($responsearray)[0];



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "View Company Details";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);





        return $branchdetails;
    }



    private function Companytype($companytypeid){

        if($companytypeid == CompanyTypeEnums::Public){

            return "Public";
        }
        return "Private";

    }



    private function ApprovalStatus($statusid){

        if($statusid == CompanyApprovalRequestEnumns::Pending){

            return "Awaiting NSS Approval";
        }

        if($statusid == CompanyApprovalRequestEnumns::Approved){

            return "Approved";
        }

        if($statusid == CompanyApprovalRequestEnumns::Rejected){

            return "Rejected";
        }

        return "";

    }


    private function addbranch($branchdetails,$createdby,$companyid){

        $branch = new Branch();

        $branchoperations = new BranchesDBOperations($branch);

        $regiondbops = new RegionDBOperations(new Region());


        $branchdetails['created_by'] = $createdby;

     //   dd($companyid);

        $branchdetails['companyid'] = $companyid;

        $branchdetails['branchregionname'] = $regiondbops->find($branchdetails['regionid'])->regionname;



        $branchdetails['IsActive'] = true;

      //  dd($branchdetails);


        $branchoperations->create($branchdetails);

        return $branchdetails;

    }




    public function listbranches($params){

        $branch = new Branch();

        $branchoperations = new BranchesDBOperations($branch);

        $regiondbops = new RegionDBOperations(new Region());





       return $branchoperations->listall()->where('companyid','=', $params["companyid"]);

    //    return $branchoperations;

    }



    private function addimages($imageurl,$createdby,$companyid){

        $companyimage = new Companyimage();

        $companydboperations = new CompanyImagesDBOperations($companyimage);

        $companydboperations->create(array('imageurl'=>$imageurl,
            'created_by'=>$createdby,
            'companyid'=>$companyid
            ));

    }



    public function checkcompanystatus($companyid){



        $companydboperations = new CompanyDBOperations(new Company());




        $companydetails = $companydboperations->find($companyid);

        $isconnectedtonss = false;

        $approvalstatusname = "Stale";

        $approvalstatusid = 0;

        $isapproved = false;

        $flairapprovalstatusname = "Stale";


        $flairapprovalstatusid = 0;

        $isflairapproved = false;

       // dd($companydetails->registeredcompanyname);


        if ($companydetails->requestapprovalstatus != null) {


            $nssapprovalstatus = DB::table('Companynssapprovalstatus')->find($companydetails->requestapprovalstatus);

            $approvalstatusname = $nssapprovalstatus->statusname;

            $approvalstatusid = $nssapprovalstatus->id;

            $isapproved = false;


            if($companydetails->requestapprovalstatus == CompanyApprovalRequestEnumns::Approved){


                $isapproved = true;
                $isconnectedtonss = true;
            }

        }


     //   dd($companydetails->flairrequeststatus);

        if ($companydetails->flairrequeststatus != null) {


            $nssapprovalstatus = DB::table('Companynssapprovalstatus')->find($companydetails->flairrequeststatus);



            $flairapprovalstatusname = $nssapprovalstatus->statusname;

            $flairapprovalstatusid = $nssapprovalstatus->id;

            $isflairapproved = false;


            if($companydetails->flairrequeststatus == CompanyApprovalRequestEnumns::Approved){


                $isflairapproved = true;

            }

        }


            return array('nss_states'=>array(
                               'isconnected'=>$isconnectedtonss,
                                'approvalstatus'=>$approvalstatusname,
                                 'approvalstatusid'=> $approvalstatusid,
                                  'isapproved'=>$isapproved

                ),
                'company_states'=>array(

                    'approvalstatus'=>$flairapprovalstatusname,
                    'approvalstatusid'=> $flairapprovalstatusid,
                    'isapproved'=>$isflairapproved
            ));

    }





    public function nssapproveorrejectcompanyrequest($params) : array
    {



        $company = new Company();

        $user = new User();

        $companydboperations = new CompanyDBOperations($company);

        $userdboperations = new UserDBOperations($user);




        $companydetails = $companydboperations->find($params['companyid']);


        if($companydetails == null){

                $this::StopProcessAndDisplayMessage(401,"company does not exist");

        }


        if($companydetails->requestsentby == null){

            $this::StopProcessAndDisplayMessage(401,"user has not submitted request to connect to NSS");

        }


        $userhascompany = $userdboperations->find($companydetails->requestsentby);

        if($userhascompany!=null){

            if($userhascompany->employerid==null){

                //    $this::StopProcessAndDisplayMessage(401,"user has not signed up for company");

            }


        }


        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Approve/Reject Company Request";

          //  dd($userhascompany->email);


            if($userhascompany->isverifiedasuseragent != true){


                //for testing purposes comment out
           //     $this::StopProcessAndDisplayMessage(401,"user is not verified on NSS");

            }



            $companydboperations->updateById($companydetails->id,array('requestapprovalstatus'=>$params['approvalstatus'],
                'requestactedonby'=>$params['userid'],
              //  'requestsenton'=>now()
            ));



            if($params['approvalstatus'] == CompanyApprovalRequestEnumns::Approved) {


                //would need the apikey of this current user to create

                $nssapi = new NSSApis("");


                $userauthresponse = $nssapi->authenticateuser($userhascompany->email);


                if ($userauthresponse["status"] == false) {


                    $this::StopProcessAndDisplayMessage(401, "user is not verified on NSS");
                }


                $userdboperations->updateById($userhascompany->id, array('nsssyncapikey' => $userauthresponse['ua_api_key']));

            }


        }catch (\Exception $ex){

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Submitting Company Request";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);

        return $this::responseHelper($responsearray);
    }




    public function flairapproveorrejectcompanyrequest($params) : array
    {



        $company = new Company();

        $user = new User();

        $companydboperations = new CompanyDBOperations($company);

        $userdboperations = new UserDBOperations($user);




        $companydetails = $companydboperations->find($params['companyid']);


        if($companydetails == null){

            $this::StopProcessAndDisplayMessage(401,"company does not exist");

        }


        $userhascompany = $userdboperations->find($companydetails->requestsentby);

        if($userhascompany!=null){

            if($userhascompany->employerid==null){

                //    $this::StopProcessAndDisplayMessage(401,"user has not signed up for company");

            }


        }


        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

          //  $params['activityname'] = "Approve/Reject Company Request";

            //   dd($userhascompany->email);





            $companydboperations->updateById($companydetails->id,array('flairrequeststatus'=>$params['approvalstatus'],
                'flairrequestactedonby'=>$params['userid'],
                'flairrequestactedon'=>now()

            ));


        }catch (\Exception $ex){

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Submitting Company Request";

            $params['responsemessage'] = $ex->getMessage();

            $this::StopProcessAndDisplayMessage("500",$ex->getMessage());
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);

        return $this::responseHelper($responsearray);
    }




}
