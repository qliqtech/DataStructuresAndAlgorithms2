<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['middleware' => ['cors', 'json.response']], function () {
    // ...

    // public routes
    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
    Route::post('/confirm_account','Auth\ApiAuthController@confirmaccount');
    Route::post('/testredis','Auth\ApiAuthController@testredis');
    Route::post('/reset_password','Auth\ApiAuthController@resetpassword');


    //


    Route::post('/send_password_reset_link','Auth\ApiAuthController@sendpasswordresetlink');//->middleware('auth:api');//->middleware('api.superAdmin');


  //  Route::post('/createassignment','AssignmentController@createassignment');//->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/user_info','Auth\ApiAuthController@userinfo')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/candidateonboarding/search_nsp_with_nssnumber_and_dob','CandidateOnboardingController@searchnspwithnssnumberanddob');//;->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/flairadminonboarding/sendin_vite','FlairAdminOnBoardingController@sendinvite')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::post('/useronboarding/revoke_invite','FlairAdminOnBoardingController@revokeinvite')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/useronboarding/resend_invite','FlairAdminOnBoardingController@resendinvite')->middleware('auth:api');//->middleware('api.superAdmin');




    Route::post('/nssadminonboarding/send_invite','NSSAdminController@sendinvite')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/companyadmin/send_invite','CompanyAdminController@sendinvite')->middleware('auth:api');//->middleware('api.superAdmin');

 //   Route::post('/companyadmin/send_invite','CompanyAdminController@sendinvite')->middleware('auth:api');//->middleware('api.superAdmin');

    //


    Route::post('/companyadmin/register_company_rep','CompanyAdminController@registercompanyrep')->middleware('auth:api');//->middleware('api.superAdmin');



    Route::get('/useronboarding/get_userdetails_from_confirmation_code','CompanyAdminController@getuserdetailsfromconfirmationcode')->middleware('auth:api');//->middleware('api.superAdmin');





    Route::post('/candidateonboarding/register_candidate','CandidateOnboardingController@registercandidate');//->middleware('auth:api');//->middleware('api.superAdmin');



    Route::get('/companyonboarding/list_industries','CompanyOnBoardingController@listindustries')->middleware('auth:api');//->middleware('api.superAdmin');
    Route::get('/companyonboarding/list_regions','CompanyOnBoardingController@listregions')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/companyonboarding/verify_ghanapostgps','CompanyOnBoardingController@verifyghanapostgps')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/companyonboarding/register_company','CompanyOnBoardingController@registercompany')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/companyonboarding/complete_company_registration','CompanyOnBoardingController@completecompanyregistration')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/companyonboarding/list_company_jobroles','CompanyOnBoardingController@listcompanyjobroles');//->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/companyonboarding/view_company_details','CompanyOnBoardingController@viewcompanydetails')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/companyonboarding/view_company_details','CompanyOnBoardingController@viewcompanydetails')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/companyonboarding/verify_company_details','CompanyOnBoardingController@verifycompanydetails')->middleware('auth:api');//->middleware('api.superAdmin');




    Route::post('/syncaccounts/submit_company_request','SyncAccountsController@submitcompanyrequest')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/syncaccounts/search_company','SyncAccountsController@searchcompany')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/syncaccounts/check_company_status','SyncAccountsController@checkcompanystatus')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/syncaccounts/nss_approve_or_reject_company','SyncAccountsController@nssapproveorrejectcompany')->middleware('auth:api');//->middleware('api.superAdmin');



    Route::post('/syncaccounts/flair_approve_or_reject_company','SyncAccountsController@flairapproveorrejectcompany')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::post('/syncaccounts/add_user_to_nss','SyncAccountsController@addusertonss')->middleware('auth:api');//->middleware('api.superAdmin');





    Route::get('/syncaccounts/view_company_details_for_approval','SyncAccountsController@viewcompanydetailsforapproval')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/syncaccounts/list_districts_on_nss','SyncAccountsController@listdistrictsonnss')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/syncaccounts/list_regions_on_nss','SyncAccountsController@listregionsonnss')->middleware('auth:api');//->middleware('api.superAdmin');



    Route::get('/syncaccounts/list_companies_for_nss_approval','SyncAccountsController@listcompaniesfornssapproval')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/syncaccounts/list_companies_for_flair_approval','SyncAccountsController@listcompaniesforflairapproval')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::post('/fileupload/upload_file_public','FileUploadController@uploadfile_public')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/fileupload/upload_file_private','FileUploadController@uploadfile_private')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::post('/fileupload/delete_file','FileUploadController@deletefile')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/filemanager/download_private_file','FileUploadController@downloadprivatefile')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/companyonboarding/list_company_branches','CompanyOnBoardingController@listcompanybranches')->middleware('auth:api');//->middleware('api.superAdmin');

    // ...
});

Route::get('/autherror', 'Auth\ApiAuthController@authenticationerror')->name('autherror');


Route::group(['middleware' => ['auth:api', 'user_accessible']], function () {

});


