<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
     * Return a reset token to the given user.
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

    /**
     * Reset the given user's password. It users reset token.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );
        if ($request->wantsJson()) {
            if ($response == Password::PASSWORD_RESET) {
                return Json::response(false, trans('passwords.reset'), null, 200);
            } else {
                return Json::response(true, trans($response), $request->input('email'), 202);
            }
        }
    }
}
