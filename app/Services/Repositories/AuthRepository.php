<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Interface\AuthInterface;
use App\Http\Resources\EmployeeResource;
use App\Notifications\EmployeeAddNotification;

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
                    'user' => new EmployeeResource(Auth::user()),
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
                return Base::fail('User not logged in');
            $user = Auth::user()->token();
            $user->revoke();
            return Base::pass('Logout successfully');
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

            $accessToken = $user->createToken('authToken')->accessToken;
            $data = [
                'token' => $accessToken,
                'user' => new EmployeeResource($user),
            ];
            return Base::success('User Information', $data);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function addEmployee($request, $usertype)
    {
        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'usertype' => 'employee',
                'designation' => $request->designation,
            ]);

            //  if (!$user->success)  return Base::fail('User not Created!');

            $messages = [
                'username' => $user->username,
                'password' => "Your password is: " . $request->password,
                'designation' => "Your designation is: " . $request->designation,
            ];
            $user->notify(new EmployeeAddNotification($messages));

            $accessToken = $user->createToken('authToken')->accessToken;

            $data = [
                'token' => $accessToken,
                'user' => new EmployeeResource($user),
            ];
            return Base::pass('User registered successfully', $data);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
}
