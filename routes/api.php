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
// Guest routes
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@create');

// Social Auth
Route::get('auth/social', 'Auth\SocialAuthController@show')->name('social.login');
Route::get('oauth/{driver}', 'Auth\SocialAuthController@redirectToProvider')->name('social.oauth');
Route::get('oauth/{driver}/callback', 'Auth\SocialAuthController@handleProviderCallback');

// Activate user
Route::post('user/activate', 'Auth\ActivationController@activate');

// Auth routes for confirmed users
Route::middleware(['auth:api', 'confirmedUser'])->group(function () {

    // Auth user route
    Route::get('/user', function (Request $request) {
        return response()->json([
            'error' => false,
            'message' => null,
            'user' => $request->user()
        ]);
    });
});

Route::get('/test', function () {
    return "hello";
});
