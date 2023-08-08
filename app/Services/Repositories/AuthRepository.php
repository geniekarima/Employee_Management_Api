<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

 use App\Services\Interface\AuthInterface;

class AuthRepository implements AuthInterface
{
    use Base;

    public function login($request, $usertype)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            $user = User::where('email', $request->email)->first();

            if (!$user)
                return Base::fail('User not found');


            if ($user->usertype != $usertype)
                return Base::fail('You can not login with this account');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $accessToken = $user->createToken('authToken')->accessToken;
                $data = [
                    'token' => $accessToken,
                    'user' => new UserResource(Auth::user()),
                ];
                return Base::pass('User login successfully', $data);
            } else {
                return Base::fail('Invalid Credentials');
            }

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function logout($request)
    {
        try {
            if (!Auth::user())
                return Base::fail('User not logged in'); // DONE
            $user = Auth::user()->token();
            $user->revoke();
            return Base::pass('Logout successfully'); // DONE
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function getUser()
    {
        try {
            $user = Auth::user();

            if (!$user)
                return Base::fail('User not found!');

            $data = [
                'id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
                'usertype' => $user->usertype,
            ];
            return Base::success('User Information', $data);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}
