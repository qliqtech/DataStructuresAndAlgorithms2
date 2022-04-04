<?php

namespace App\ImplementationService;



use App\CacheOperations\CacheCompanyRegistrationOps;
use App\CacheOperations\CacheUserRegistrationOps;
use App\DBOperations\CompanyDBOperations;
use App\DBOperations\JobRolesDBOperations;
use App\DBOperations\UserDBOperations;
use App\Enums\InAppResponsTypes;
use App\Enums\UserRoles;
use App\Helper\EmailHelper;
use App\Helper\EmailMessages;
use App\Helper\FlairInternalAPIs;
use App\Helper\GenerateRandomCharactersHelper;
use App\Helper\LoginHelper;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\User;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class AuthenticationService extends BaseImplemetationService
{


    public function registeruser($params) : array
    {

        $user = new User();

        $userregistrationcacheoperations = new CacheUserRegistrationOps();

        $userdboperations = new UserDBOperations($user);

        $jobroleops = new JobRolesDBOperations(new Jobrole());


        $emailmessages = new EmailMessages();

        $responsearray = array();

        $params =  array_add($params,'Isconfirmed',false);



        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }



            $params['password'] = LoginHelper::HashPassWord($params['password']);  //Hash::make($params['password']);

            $params['remember_token'] = GenerateRandomCharactersHelper::generaterandomAlphabets(10);

            $params['activityname'] = "User Signup";

            // $params['confirmationcode'] = GenerateRandomCharactersHelper::generaterandomAlphabets(20);

            $params['activityname'] = "User Signup";


            $params['jobrolename'] = $jobroleops->find($params["jobroleid"])->JobRoleName;

            $params['userroleid'] = UserRoles::CompanyAdmin;

            $params['onboardingcompanystate'] = "incomplete";



            // dd($params);

            //    dd($params['confirmationcode']);

            $userregistrationcacheoperations->cacheuserregistrationdetails($params['confirmationcode'],$params);



            //  $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                //       InAppResponsTypes::responsemessagekey => 'confirmation code:'. $params['confirmationcode']
            );
            //   return $this::responseHelper($responsearray)[0];



        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "User Signup";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        $emailmessagebody = $emailmessages->sendEmailConfirmationEmail($params["fullname"], $params['confirmationcode']);


      //  EmailHelper::sendEmail($params["email"],"",$emailmessagebody,"Confirmation Email");

          EmailHelper::sendCUstomisedEmail($params["email"],"Activate your account",
              "Activate your account",
              $params["fullname"],
              $emailmessagebody,$emailmessagebody);

        return $this::responseHelper($responsearray)[0];
    }




    public function completecompanyrepregistration($params) : array
    {

        $user = new User();

        $userregistrationcacheoperations = new CacheUserRegistrationOps();

        $userdboperations = new UserDBOperations($user);

        $jobroleops = new JobRolesDBOperations(new Jobrole());


        $emailmessages = new EmailMessages();

        $responsearray = array();

        $params =  array_add($params,'Isconfirmed',false);



        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }



            $params['password'] = LoginHelper::HashPassWord($params['password']);  //Hash::make($params['password']);

            $params['remember_token'] = GenerateRandomCharactersHelper::generaterandomAlphabets(10);

            $params['activityname'] = "Company Rep Signup";

            $params['useraccountstatus'] = "active";

            $params['Isconfirmed'] = 1;

            $params['ConfirmedOn'] = now();

            $params['jobrolename'] = $jobroleops->find($params["jobroleid"])->JobRoleName;



            $userdetails =User::where('confirmationcode',$params['confirmationcode'])->first();

            if($userdetails == null){

                $this->StopProcessAndDisplayMessage(404,"Code not found");

            }



             $userdboperations->updateById($userdetails->id, $params);

            // dd($params);

            //    dd($params['confirmationcode']);

        //    $userregistrationcacheoperations->cacheuserregistrationdetails($params['confirmationcode'],$params);



              $token = $userdetails->createToken('Laravel Password Grant Client')->accessToken;

                $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                       'token' => $token,
                        'usermeta' =>$this->userinfo($userdetails->id)

            );
            //   return $this::responseHelper($responsearray)[0];



        }catch (\Exception $ex){


               dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "User Signup";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);



        return $this::responseHelper($responsearray)[0];
    }





    public function registercandidate($params) : array
    {

        $user = new User();

        $userregistrationcacheoperations = new CacheUserRegistrationOps();

        $userdboperations = new UserDBOperations($user);

        $flairapi = new FlairInternalAPIs("");

        $emailmessages = new EmailMessages();

        $responsearray = array();

        $params =  array_add($params,'Isconfirmed',false);

        $nspdetails = $flairapi->getNSPDetailsbyNSSnumber($params["nssnumber"]);

        if($nspdetails == null){

            $this::StopProcessAndDisplayMessage(404,"NSS number not found");

        }


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

         //   $userrealm = DB::table("userroles")->where()

            $params['password'] = LoginHelper::HashPassWord($params['password']);  //Hash::make($params['password']);

            $params['remember_token'] = GenerateRandomCharactersHelper::generaterandomAlphabets(10);

            $params['activityname'] = "Candidate Signup";


            $params['userroleid'] = UserRoles::NSSCandidate;


            $params['front_portrait_url'] = $nspdetails["front_portrait_url"];

            $params['nssnumber'] = $nspdetails["nss_number"];

            //    dd($params);


            $userregistrationcacheoperations->cacheuserregistrationdetails($params['confirmationcode'],$params);



            //  $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
            );


            $emailmessagebody = $emailmessages->sendEmailConfirmationEmail($params["fullname"], $params['confirmationcode']);


            EmailHelper::sendCUstomisedEmail($params["email"],"Activate your account",
                "Activate your account",
                $params["fullname"],
                $emailmessagebody,$emailmessagebody);


        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "User Signup";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);



        return $this::responseHelper($responsearray)[0];
    }





    public function registerflairadmin($params) : array
    {

        $user = new User();


        $userdboperations = new UserDBOperations($user);



        $emailmessages = new EmailMessages();

        $responsearray = array();

        $params =  array_add($params,'Isconfirmed',false);


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }


            $params['activityname'] = "Flair Admin Signup";



            $params['userroleid'] = UserRoles::FlairAdmin;


            $params['useraccountstatus'] = "pending";

           // $params['IsActive'] = false;


            $userdboperations->create($params);


            //sendemail

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
            );

            $emailmessagebody = $emailmessages->sendInvitationEmail($params["fullname"], $params['confirmationcode']);


            EmailHelper::sendCUstomisedEmail($params["email"],"Flair Invitation",
                "Join Flair",
                $params["fullname"],
                $emailmessagebody,$emailmessagebody);




        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "User Signup";

            $params['responsemessage'] = $ex->getMessage();


         //   echo $ex->getMessage();die();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        return $this::responseHelper($responsearray)[0];
    }





    public function registercompanyrep($params) : array
    {

        $user = new User();


        $userdboperations = new UserDBOperations($user);


        $emailmessages = new EmailMessages();

        $responsearray = array();

        $params =  array_add($params,'Isconfirmed',false);


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }


            $params['activityname'] = "Flair Admin Signup";


            $params['userroleid'] = UserRoles::CompanyRep;


            $params['useraccountstatus'] = "pending";

            // $params['IsActive'] = false;


            $userdboperations->create($params);


            //sendemail

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
            );

            $emailmessagebody = $emailmessages->sendInvitationEmail("", $params['confirmationcode']);


            EmailHelper::sendCUstomisedEmail($params["email"],"Flair Invitation",
                "Join Flair",
                "",
                $emailmessagebody,$emailmessagebody);




        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "User Signup";

            $params['responsemessage'] = $ex->getMessage();


               echo $ex->getMessage();die();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        return $this::responseHelper($responsearray)[0];
    }




    public function registernssadmin($params) : array
    {

        $user = new User();


        $userdboperations = new UserDBOperations($user);



        $emailmessages = new EmailMessages();

        $responsearray = array();

        $params =  array_add($params,'Isconfirmed',false);


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }


            $params['activityname'] = "NSS Admin Signup";



            $params['userroleid'] = UserRoles::NSSAdministrator;


            $params['useraccountstatus'] = "pending";

            // $params['IsActive'] = false;


            $userdboperations->create($params);


            //sendemail

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
            );

            $emailmessagebody = $emailmessages->sendInvitationEmail($params["fullname"], $params['confirmationcode']);


            EmailHelper::sendCUstomisedEmail($params["email"],"Flair Invitation",
                "Join Flair",
                $params["fullname"],
                $emailmessagebody,$emailmessagebody);




        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "User Signup";

            $params['responsemessage'] = $ex->getMessage();


            //   echo $ex->getMessage();die();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        $emailmessagebody = $emailmessages->sendEmailConfirmationEmail($params["fullname"], $params['confirmationcode']);



        return $this::responseHelper($responsearray)[0];
    }





    public function revokeinvite($params) : array
    {

        $user = new User();


        $userdboperations = new UserDBOperations($user);



        $emailmessages = new EmailMessages();

        $responsearray = array();

      //  $params =  array_add($params,'Isconfirmed',false);


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }


            $params['activityname'] = "Invite Revoke";


            $params['userroleid'] = UserRoles::FlairAdmin;


            $params['useraccountstatus'] = "pending";

            // $params['IsActive'] = false;


            $userdetails = $userdboperations->find($params["selecteduserid"]);


            if($userdetails == null){

                $this::StopProcessAndDisplayMessage(404,"User not found");
            }

            //    dd($userdetails->useraccountstatus);

            if($userdetails->useraccountstatus == "pending"){


                    $userdetails->delete();

            }else{


                $this::StopProcessAndDisplayMessage(401,"Invitation is already accepted");

            }

            //sendemail

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
            );




        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Revoke Invite";

            $params['responsemessage'] = $ex->getMessage();


            //   echo $ex->getMessage();die();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);



        return $this::responseHelper($responsearray)[0];
    }





    public function resendinvite($params) : array
    {

        $user = new User();


        $userdboperations = new UserDBOperations($user);



        $emailmessages = new EmailMessages();

        $responsearray = array();

        //  $params =  array_add($params,'Isconfirmed',false);


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }


            $params['activityname'] = "Invite Resent";


            $userdetails = $userdboperations->find($params["selecteduserid"]);


            if($userdetails == null){

                $this::StopProcessAndDisplayMessage(404,"User not found");
            }

            //    dd($userdetails->useraccountstatus);

            if($userdetails->useraccountstatus == "pending"){


            }else{


                $this::StopProcessAndDisplayMessage(401,"Invitation is already accepted");

            }

            //sendemail

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
            );


            $emailmessagebody = $emailmessages->sendInvitationEmail("", $userdetails->confirmationcode);


            //  EmailHelper::sendEmail($params["email"],"",$emailmessagebody,"Confirmation Email");

            EmailHelper::sendCUstomisedEmail($userdetails->email,"Activate your account",
                "Activate your account",
                "",
                $emailmessagebody,$emailmessagebody);


        }catch (\Exception $ex){


            //   dd($ex->getMessage());

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Revoke Invite";

            $params['responsemessage'] = $ex->getMessage();


            //   echo $ex->getMessage();die();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);



        return $this::responseHelper($responsearray)[0];
    }





    public function confirmaccount($params) : array
    {


        $user = new User();

        $userdboperations = new UserDBOperations($user);

        $cacheUserRegistrationOps = new CacheUserRegistrationOps();

        $responsearray = array();



        try {

            if ($params == null) {

                return $this::responseHelper($responsearray)[0];
            }

            $valuesfromcache = json_decode($cacheUserRegistrationOps->getuserregistrationdetailsfromcache($params["confirmationcode"]),true);

            //  dd($valuesfromcache);

            if($valuesfromcache == null){

                $this::StopProcessAndDisplayMessage("404","Invalid Confirmation code");
            }

            $valuesfromcache["Isconfirmed"] = true;

            $valuesfromcache["ConfirmedOn"] = now();

            $valuesfromcache["IsActive"] = true;

            $valuesfromcache["useraccountstatus"] = "active";


            //   dd($valuesfromcache);

            if($userdboperations->findUserByEmail($valuesfromcache["email"])!=null){


                $this::StopProcessAndDisplayMessage("201","Account already confirmed");

            }

            $userdetails = $userdboperations->create($valuesfromcache);



            if($userdetails == null){

                $this::StopProcessAndDisplayMessage("404","Confirmation code not found");

            }

            $params['userid'] = $userdetails->id;


            $token = $userdetails->createToken('Laravel Password Grant Client')->accessToken;

            $cacheUserRegistrationOps->deleteconfirmationcode($params["confirmationcode"]);

            return   $responsearray = array('responsecode' => 200,
                'responsemessage'=>"Account confirmed successfully",
                'usertoken'=>$token,
                'usermeta'=> $this->userinfo($userdetails->id)
            );

        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );


            //   echo $ex->getMessage();die();


            $this::StopProcessAndDisplayMessage(500,$ex->getMessage());
            $params['activityname'] = "Account Confirmation";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //

        return $this::responseHelper($responsearray)[0];
    }




    public function resetpassword($params) : array
    {


        $user = new User();

        $userdboperations = new UserDBOperations($user);

        $cacheUserRegistrationOps = new CacheUserRegistrationOps();

        $responsearray = array();



        try {

            if ($params == null) {

                return $this::responseHelper($responsearray)[0];
            }

            //    $valuesfromcache = json_decode($cacheUserRegistrationOps->getuserregistrationdetailsfromcache($params["confirmationcode"]),true);

            $newpassword = LoginHelper::HashPassWord($params['password']);

            $userdetails = $userdboperations->updateuseraccountpasswordfromconfirmationcode($params["confirmationcode"],$newpassword);


            if($userdetails == null){

                $this::StopProcessAndDisplayMessage("404","Confirmation code not found");

            }

            $params['userid'] = $userdetails->id;


            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            $userdboperations->updateById($userdetails->id,array('IsActive'=>true,'useraccountstatus'=>'active','Isconfirmed'=>true));


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                InAppResponsTypes::responsemessagekey => $token
            );

            $params['activityname'] = "Password reset";

            $params['responsemessage'] = "password reset successful";

        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Password reset";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //    $cacheUserRegistrationOps->deleteconfirmationcode($params["confirmationcode"]);

        return $this::responseHelper($responsearray)[0];
    }




    public function sendpasswordresetlink($params)      {


        $user = new User();

        $userdboperations = new UserDBOperations($user);

        $cacheUserRegistrationOps = new CacheUserRegistrationOps();

        $responsearray = array();

        if ($params == null) {

            return $this::responseHelper($responsearray)[0];
        }

        $userdetails = $userdboperations->findUserByEmail($params["email"]);




        if($userdetails == null){

            return  $this::StopProcessAndDisplayMessage("404","account does not exist");


        }

        if($userdetails->IsDeleted == true){

            return  $this::StopProcessAndDisplayMessage("404","account is deleted");


        }

        if($userdetails->IsActive == false){


            $this::StopProcessAndDisplayMessage("401","account is deactivated");

        }

        try {



            //  $valuesfromcache = json_


            $token = $userdetails->createToken('Laravel Password Grant Client')->accessToken;


            $emailmessages = new EmailMessages();

            $confirmationcode = GenerateRandomCharactersHelper::generaterandomAlphabets(16);



            $emailmessagebody = $emailmessages->sendPasswordresetlink("", $confirmationcode);



            $userdboperations->updateById($userdetails->id,array('confirmationcode'=>$confirmationcode,'requirespasswordreset'=>true));

            //confirmationcode


            EmailHelper::sendEmail($params["email"],"",$emailmessagebody,"Password reset");

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                InAppResponsTypes::responsemessagekey => $token,
                'confirmationcodefortest'=>$confirmationcode
            );



        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Account Confirmation";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //    $cacheUserRegistrationOps->deleteconfirmationcode($params["confirmationcode"]);

        return $this::responseHelper($responsearray)[0];
    }




    private function checkuseraccessattributes($userdetails){

        if($userdetails == null){

            $this->StopProcessAndDisplayMessage("404","User not found");

        }

        if($userdetails->IsDeleted == true){

            $this->StopProcessAndDisplayMessage("401","Account is Deleted");

        }


        if($userdetails->IsConfirmed !=true){

            $this->StopProcessAndDisplayMessage("401","Account not confirmed");

        }



        if($userdetails->IsActive == false){

            $this->StopProcessAndDisplayMessage("401","Account is Deactivated");

        }

    }


    public function login($params) : array
    {

        $user = new User();

        $userdboperations = new UserDBOperations($user);

        $responsearray = array();


        if ($params == null) {


            return $this::responseHelper($responsearray)[0];
        }


        $userdetails =  $userdboperations->findUserByEmail($params['email']);




        if($userdetails){


         //   dd($userdetails->password);
            try {
                if (LoginHelper::PasswordCheck($params['password'], $userdetails->password)) {




                    $token = $userdetails->createToken('Laravel Password Grant Client')->accessToken;

                    $successful = true;

                    if($successful == true){

                        $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                            InAppResponsTypes::responsemessagekey => $token,
                            'usermeta'=>$this->userinfo($userdetails->id)
                        );



                        $params['userid'] = $userdetails->id;

                        $params['activityname'] = "Login";

                        $params['responsemessage'] = "Login Success ";



                    }


                } else {
                    $params['responsemessage'] = "Wrong email or password: ".$params['email'];

                    $params['activityname'] = "Login";



                    $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Failed,
                        InAppResponsTypes::responsemessagekey => "Wrong username or password"
                    );
                }





            }catch (\Exception $ex){


                $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                    InAppResponsTypes::responsemessagekey => $ex->getMessage()
                );

                $params['activityname'] = "Login";


            //    echo $ex->getMessage();die();
                $params['responsemessage'] = $ex->getMessage();

                //    return $this::responseHelper($responsearray)[0];
            }

        }else{

            $params['responsemessage'] = "Account not found ".$params['email'];

            $params['activityname'] = "Login";



            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Failed,
                InAppResponsTypes::responsemessagekey => "Account not found");
        }


        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);

        return $this::responseHelper($responsearray)[0];
    }



    public function userinfo($userid){

        $userdbops = new UserDBOperations(new User());

        $companydbops = new CompanyDBOperations(new Company());

        $companyservice = new CompanyService();


        $userdetais = DB::table('users')->select(['id','fullname','email','userroleid','onboardingcompanystate','useraccountstatus','front_portrait_url','employerid'])->find($userid);

       // dd($userdetais);

        $userroledetails = DB::table('userroles')->find($userdetais->userroleid);

        //  dd($userroledetails);

        if($userdetais->userroleid == UserRoles::CompanyAdmin || $userdetais->userroleid == UserRoles::CompanyRep){


            $companydetail = $companydbops->find($userdetais->employerid);

            if($companydetail == null){

                $empty = (object)NULL;


                return array('userdetails'=>$userdetais,
                    'userrole'=>$userroledetails,
                    'companydetails'=>  $empty);


            }

            $companycachedata = new CacheCompanyRegistrationOps();

            // dd($userdetais->employerid);

            $companyinfofromcache = json_decode($companycachedata->getcompanydetailsfromcache($userdetais->employerid),true);

            if($companyinfofromcache == null){

                $companyinfofromcache =  json_decode($companydetail->companydetailscache,true);


            }

            $companystatus = $companyservice->checkcompanystatus($userdetais->employerid);

         //   dd($companystatus);

            $companyinfofromcache["company_status"] = $companystatus;

            //    dd($companyinfofromcache);

            return array('userdetails'=>$userdetais,
                'userrole'=>$userroledetails,
                'companydetails'=>  $companyinfofromcache

            );

        }
        if($userdetais->userroleid == UserRoles::NSSCandidate){

            //   dd($userroledetails);

            return array('userdetails'=>$userdetais,
                'userrole'=>$userroledetails,

            );



        }



            //   dd($userroledetails);

        return array('userdetails'=>$userdetais,
            'userrole'=>$userroledetails,

        );


    }









}
