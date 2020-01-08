<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login','userController@login');
Route::post('register','userController@store');
Route::post('recover_password','userController@recoverPassword');


Route::group(['middleware' => ['auth']], function (){

	Route::apiResource('application','applicationController');
	Route::apiResource('restriction','restrictionController');
	Route::apiResource('users', 'userController');
});