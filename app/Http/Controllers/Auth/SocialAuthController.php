<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Social Auth Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the authentication with social media
    |
    */

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * List of providers configured in config/services acts as whitelist
     *
     * @var array
     */
    protected $providers = [
        'github',
        'facebook',
        'google',
        'twitter'
    ];

    /**
     * Redirect to provider for authentication
     *
     * @param $driver
     * @return mixed
     */
    public function redirectToProvider($driver)
    {
        if (!$this->isProviderAllowed($driver)) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {
            return Socialite::driver($driver)->stateless()->redirect();
        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }

    /**
     * Handle response of authentication redirect callback
     *
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->stateless()->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        // check for email in returned user
        return empty($user->email)
            ? $this->sendFailedResponse("No email id returned from {$driver} provider.")
            : $this->loginOrCreateAccount($user, $driver);
    }


    /**
     * Send a failed response with a msg
     *
     * @param null $msg
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedResponse($msg = null)
    {
        return response()->json(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    }

    protected function loginOrCreateAccount($providerUser, $driver)
    {
        // check for already has account
        $user = User::where('email', $providerUser->getEmail())->first();

        // if user already found
        if ($user) {
            // update the avatar and provider that might have changed
            $user->provider = $driver;
            $user->save();
            $token = $user->createToken('Access Token')->accessToken;

        } else {
            // create a new user
            $user = User::create([
                'fullname' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'cover' => $providerUser->getAvatar(),
                'provider' => $driver,
                'confirmed_at' => Carbon::now(),
                // user can use reset password to create a password
                'password' => ''
            ]);

            $token = $user->createToken('Access Token')->accessToken;
        }

        $data = [
            'access_token' => $token
        ];

        return JSON::response(false, 'login success!', $data, 200);
    }

    /**
     * Check for provider allowed and services configured
     *
     * @param $driver
     * @return bool
     */
    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }

}
