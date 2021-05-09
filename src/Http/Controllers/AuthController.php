<?php

namespace MadBoyDevelopers\LaravelAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use MadBoyDevelopers\LaravelAuth\Models\User;

class AuthController extends Controller
{
    /**
     * Login api method
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        if (Auth::check())
            return json_response(true, 'User is already logged in!');

        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required|password'
        ]);

        if ($validator->fails())
            return json_response(false, $validator->errors()->first());

        /** @var User $user_class */
        $user_class = config('laravel-auth.models.user', User::class);
        /*
         * Setting up User Model using config model path.
         */
        Auth::setUser($user_class);

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return json_response(true, trans('laravel-auth::laravel-auth.success_login'));
        }

        return json_response(false, trans('laravel-auth::laravel-auth.email_password_wrong'));
    }
}
