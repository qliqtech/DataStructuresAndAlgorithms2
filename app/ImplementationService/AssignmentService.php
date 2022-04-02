<?php

namespace App\ImplementationService;

use App\DBOperations\AssignmentDBOperations;
use App\DBOperations\UserDBOperations;
use App\Enums\InAppResponsTypes;
use App\Models\Assignment;
use App\Models\Assignment_students;
use App\Models\User;



class AssignmentService extends BaseImplemetationService
{

    public $params;



    function __construct($parameters) {
        $this->params = $parameters;

        if($this->params["creatingcompany"] != true){

           $this::performusercompanyvalidationschecks($parameters["userid"]);

        }

    }

    private function performusercompanyvalidationschecks($userid){

        //is user subscribed to this service
        //does user have company
        //does user have branch
        //is company deleted
        //is branch deleted
        //is company active
        //is branch active


    }


    private function checkifuseriscompanyadmin($userid){

        //is user subscribed to this service
        //does user have company
        //does user have branch
        //is company deleted
        //is branch deleted
        //is company active
        //is branch active


    }


    private function CompanyAdminCheckOwnershipOfCompany($userid,$companyid){

        //is user subscribed to this service
        //does user have company
        //does user have branch
        //is company deleted
        //is branch deleted
        //is company active
        //is branch active


    }


    private function BranchAdminCheckOwnershipofBranch($userid,$branchid){

        //is user subscribed to this service
        //does user have company
        //does user have branch
        //is company deleted
        //is branch deleted
        //is company active
        //is branch active


    }



    private function performusercompanyvalidationschecks11($userid){


        //does user have company
        //does user have branch
        //is company deleted
        //is branch deleted
        //is company active
        //is branch active


    }

    public function createassignment() : array
    {

        $params = $this->params;



        $assignment = new Assignment();

        $user = new User();


        $assignmentDBOperations = new AssignmentDBOperations($assignment);

        $useroperations = new UserDBOperations($assignment);


        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Assignment created";

          //  $params['responsemessage'] = $ex->getMessage();

         //   $assignmentcreated = $assignmentDBOperations->create($params);



            $classid = $params['classid'];



            $listofstudentsinclass = $useroperations->listall()->where('classid','=',$classid);

         //   dd($listofstudentsinclass);

          //  $this::matchassignmenttostudent($listofstudentsinclass->toArray(),$assignmentcreated->id);

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                InAppResponsTypes::responsemessagekey => "Assignment created successfully"
            );

            //send bulk email to class





        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Assignment created";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);

        //    dd('i am here');

        return $this::responseHelper($responsearray)[0];
    }


    private function matchassignmenttostudent($studentlistarray,$assignmentid){

        $assignment = new Assignment();

        $assignmentdboperations = new AssignmentDBOperations($assignment);

        foreach ($studentlistarray as $student){


            $student = array_add($student,'assignmentid',$assignmentid);

            $student = array_add($student,'userid',$student['id']);


            //   dd($student);

            $assignmentdboperations->createstudentandassignment($student);


        }

    }


    public function listassignments($params) : array
    {


        $assignment = new Assignment();

        $assignmentDBOperations = new AssignmentDBOperations($assignment);

        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Assignment List";

            //  $params['responsemessage'] = $ex->getMessage();

            $assignmentlist = $assignmentDBOperations->listall();
            //  $token = $assignment->createToken('creds')->accessToken;

            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                InAppResponsTypes::responsemessagekey => "Assignment listed successfully",
             //   ''=>''
            );




        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Assignment Listed";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);



        return $this::responseHelper($responsearray)[0];
    }




    public function markassignmentandcomment($params) : array
    {


        $assignmentstudents = new Assignment_students();


        $userassignmentid = $params['userassignmentid'];


        $assignmentDBOperations = new AssignmentDBOperations($assignmentstudents);




        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Assignment Marked";

            $params['markedon'] = \App\Helper\TimeHelper::getCurrentDateTime();

            $params['markedby'] = $params['userid'];

            $params['ismarked'] = true;


            // dd($params);



            $assignmentDBOperations->updateById(intval($userassignmentid),$params);


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                InAppResponsTypes::responsemessagekey => "Assignment Marked successfully"
            );



        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Assignment created";

            $params['responsemessage'] = $ex->getMessage();

            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);



        return $this::responseHelper($responsearray)[0];
    }




    public function submitassignment($params) : array
    {


        $assignmentstudents = new Assignment_students();


        $userassignmentid = $params['userassignmentid'];


        $assignmentDBOperations = new AssignmentDBOperations($assignmentstudents);



        $responsearray = array();


        try {

            if ($params == null) {


                return $this::responseHelper($responsearray)[0];
            }

            $params['activityname'] = "Assignment Submitted";

            $params['issubmitted'] = true;

            $params['submittedon'] = \App\Helper\TimeHelper::getCurrentDateTime();;




            $assignmentDBOperations->updateById(intval($userassignmentid),$params);


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Success,
                InAppResponsTypes::responsemessagekey => "Assignment Marked successfully"
            );


       //     $storagePath = Storage::disk('s3')->putFileAs($base_location,$request->file('fileupload'),$imageName ,'public');

         //   $objecturl = "https://inpath-logistics-hub-bucket.s3-eu-west-1.amazonaws.com/".$storagePath;




        }catch (\Exception $ex){


            $responsearray = array(InAppResponsTypes::responsetypekey => InAppResponsTypes::Error,
                InAppResponsTypes::responsemessagekey => $ex->getMessage()
            );

            $params['activityname'] = "Assignment created";

            $params['responsemessage'] = $ex->getMessage();


            //    return $this::responseHelper($responsearray)[0];
        }

        $responsearray = array_add($responsearray,'AuditItems',$params);


        //  dd($responsearray);



        return $this::responseHelper($responsearray)[0];
    }




}
