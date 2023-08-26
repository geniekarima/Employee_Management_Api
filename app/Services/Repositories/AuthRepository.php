<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Interface\AuthInterface;
use App\Http\Requests\Authentication\VerifyOTP;
use App\Notifications\OtpNotification;
use Carbon\Carbon;
use App\Http\Resources\EmployeeResource;
use App\Notifications\EmployeeAddNotification;

class AuthRepository implements AuthInterface
{
    use Base;

    public function login($request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            $user = User::where('email', $request->email)->first();

            if (!$user)
                return Base::fail('User not found');


            // if ($user->usertype != $usertype)
            //     return Base::fail('You can not login with this account');

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

    //Forgot password
    public function sendOtp($user)
    {
        try {
            $generateOTP = rand(100000, 999999);
            $user->otp = Hash::make($generateOTP);
            $user->otp_created_at = Base::now();
            $user->save();

            $messages = [
                'greeting' => 'Hi ' . $user->username,
                'body' => 'Here is your verification code that\'s valid for only 5 Mins. If its not you please ignore it.',
                'otp' => 'Verification Code: ' . $generateOTP,
            ];
            $user->notify(new OtpNotification($messages));

            return Base::pass(null, $user);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function resendOtp($request)
    {
        try {

            $user = User::where('email', strtolower($request->email))->first();
            if (!$user) return Base::fail("User not found");

            if ($user->role !== request()->header('app_role')) return Base::fail("User does not exist");

            if ($user->otp && $user->otp_created_at) {
                $dif = Base::now()->diffInSeconds($user->otp_created_at);
                if ($dif < 120) return Base::fail('Already sent OTP', (120 - $dif), 'otp_exist');
            }

            $send_otp = $this->sendOtp($user);
            if (!$send_otp->success) return Base::fail($send_otp->message);

            $data = new EmployeeResource($user);
            if ($request->forget) $data['forget_password'] = true;

            return Base::pass('OTP Code has been sent to your email', $data);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function verifyOtp($request)
    {
        try {
            $user = User::where('email', strtolower($request->email))->first();
            if (!$user) return Base::fail("User not found");

            if ($user->otp && $user->otp_created_at) {
                $dif = Base::now()->diffInSeconds($user->otp_created_at);
                if ($dif > 120) return Base::fail('OTP Code time expired');
            }

            if (!Hash::check($request->otp, $user->otp)) return Base::fail('OTP does not match');

            if ($request->forget) {
                $accessToken = rand(10000000, 99999999);
                $user->otp = $accessToken;
                $user->otp_created_at = Base::now();

                $data = [
                    'token' => $accessToken,
                    'forget_password' => true,
                    'user' => new EmployeeResource($user),
                ];
            } else {
                $accessToken = $user->createToken('authToken')->accessToken;
                $user->is_verified = true;
                $user->is_active = true;
                $user->otp = null;
                $user->otp_created_at = null;
                $data = [
                    'token' => $accessToken,
                    'user' => new EmployeeResource($user),
                ];
            }

            $user->save();
            return Base::pass('OTP verified', $data);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }


    public function resetPassword($request)
    {
        try {

            $user = User::where('email', strtolower($request->email))->first();

            if (!$user)
                return Base::fail('User not found.');

            if (!$user->otp || $user->otp !== $request->token) {
                return Base::fail('Invalid OTP token. Please try again or request a new one.');
            }
            $user->update([
                'password' => Hash::make($request->password),
                'otp' => null,
            ]);

            return Base::pass('Password has been successfully reset');

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}
