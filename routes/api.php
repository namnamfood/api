<?php

use App\Helpers\JSON;
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
// Guest routes
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@create');
Route::post('password/email', 'Auth\ResetPasswordController@getResetToken');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
// Social Auth
Route::get('oauth/{driver}', 'Auth\SocialAuthController@redirectToProvider');
Route::get('oauth/{driver}/callback', 'Auth\SocialAuthController@handleProviderCallback');

// Activate user
Route::post('user/activate', 'Auth\ActivationController@activate');

// User area for auth and confirmed users
Route::middleware(['auth:api', 'confirmedUser'])->group(function () {

    // Auth user route
    Route::get('/user', function (Request $request) {
        return JSON::response(false, null, $request->user(), 200);
    });
});

// Admin area
Route::prefix('admin')->namespace('Admin')->middleware(['auth:admin-api'])->group(function () {

    // Auth user route
    Route::get('/user', function (Request $request) {
        return JSON::response(false, null, $request->user(), 200);
    });

    Route::resource('roles', 'AdminController');
});
Route::resource('regions', 'RegionController');

Route::get('/test', function () {
    return "hello";
});