<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    /**
     * ActivationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function activate(Request $request)
    {

        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only('activation_code'), [
            'activation_code' => 'required|min:6|max:6',
        ]);

        if ($valid->fails()) {
            $errors = $valid->errors()->all();
            return response()->json([
                'error' => true,
                'message' => $errors]);
        }

        $activationCode = $request->get('activation_code');
        $user = $request->user();
        if ($user->verification_code != $activationCode) {
            return response()->json([
                "error" => true,
                'message' => 'Verification code error. Please, Check your code!',
            ]);
        }

        $user->confirmed_at = Carbon::now();
        $user->save();

        return response()->json([
            "error" => false,
            'message' => 'Thanks! You have succesfull activated your account!',
            'user' => $user,
        ]);
    }
}
