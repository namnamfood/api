<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        if ($request->wantsJson()) {
            $user = User::where('email', $request->get('email'))->first();
            if (!$user) {
                return JSON::response(false, 'not found!', null, 400);
            }
            $token = $this->broker()->createToken($user);
            $data = [
                'access_token' => $token
            ];
            // It is insecure to return token. So, we have to send this token  to the email as a parameter in the reset link..
            // if link has sent scuccefully to the mail, user will click link and open app with reset password screen from email.
            return JSON::response(false, 'forgot password', $data, 200);
        }
    }
}
