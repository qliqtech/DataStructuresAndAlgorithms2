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



    Route::get('/flairadmin/list_flair_admins','FlairAdminController@listflairadmins')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/candidate/list_candidates','CandidateController@listcandidates')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/nssadmin/listnssadmins','NSSAdminController@listnssadmins')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/companyadmin/listcompanyreps','CompanyAdminController@listcompanyreps')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::get('/user/userinfo','Auth\ApiAuthController@userinfo')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::delete('/user/deleteuser','UserController@deleteuser')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::post('/user/deactivate_user','UserController@deactivateuser')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/user/activate_user','UserController@activateuser')->middleware('auth:api');//->middleware('api.superAdmin');


    Route::post('/userrole/createrole','UserRoleController@createrole')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/userrole/userroledetail','UserRoleController@userroledetail')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/userrole/list_permissions','UserRoleController@listpermissions')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::get('/userrole/list_user_roles','UserRoleController@listuserroles')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/userrole/deactivate_user_role','UserRoleController@deactivateuserrole')->middleware('auth:api');//->middleware('api.superAdmin');

    Route::post('/userrole/activate_user_role','UserRoleController@activateuserrole')->middleware('auth:api');//->middleware('api.superAdmin');



});

Route::get('/autherror', 'Auth\ApiAuthController@authenticationerror')->name('autherror');


Route::group(['middleware' => ['auth:api', 'user_accessible']], function () {

});


