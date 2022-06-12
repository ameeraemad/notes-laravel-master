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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//===============================================================
//                 USERS AUTHENTICATION ROUTES
//===============================================================

Route::get('flutter', function () {
    return response()->json(['message' => 'Welcome in API from FLUTTER']);
});

Route::prefix('user/')->namespace('Auth')->group(function () {
    Route::post('login', 'UserApiAuthController@login');
    Route::post('register', 'UserApiAuthController@register');
});

Route::get("customers","Conntroller@method");

Route::prefix('user/')->namespace('Auth')->middleware(['auth:users_api'])->group(function () {
    Route::get('', 'UserApiAuthController@user');
});

//===============================================================
//                 BASE AUTHENTICATION ROUTES
//===============================================================
Route::prefix('user/')->namespace('Auth')->middleware(['auth:users_api'])->group(function () {
    Route::get('', 'AuthBaseController@user');
    Route::get('fcm-token/refresh', 'AuthBaseController@refreshFcmToken');
    Route::put('status-update', 'AuthBaseController@updateActivityStatus');
    Route::get('app-language', 'AuthBaseController@refreshSelectedAppLanguage');
    Route::get('logout', 'AuthBaseController@logout');

    Route::put('update', 'UserApiAuthController@update');
});

Route::namespace('API')->middleware('auth:users_api')->group(function () {
    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('notes', 'NoteController');
    Route::get('categories/{id}/notes', 'CategoryController@showCategoryNotes');
    Route::get('notes/{id}/status', 'NoteController@updateStatus');
    Route::get('user/profile/statistics', 'UserController@getStatistics');
});

Route::put("categories/{id}","CategoryController@update");
