<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use Illuminate\Support\Facades\Auth;
use App\Services\Interface\EmployeeProfileInterface;



class EmployeeProfileRepository implements EmployeeProfileInterface
{
    use Base;

    public function updateEmployeeProfile($request)
    {
        try {
            $user = User::find(Auth::user()->id);

            if (!isset($user))
                return Base::fail('User not found!');
            $user->username = isset($request->username) ? $request->username : $user->username;
            $user->phone = isset($request->phone) ? $request->phone : $user->phone;
            $user->address = isset($request->address) ? $request->address : $user->address;
            $user->birth_date = isset($request->birth_date) ? $request->birth_date : $user->birth_date;
            
            if (isset($request->image)) {
                if ($user->image) {
                    if (file_exists(public_path() . $user->image)) {
                        unlink(public_path() . '/' . $user->image);
                    }
                }
                $user->image = Base::imageUpload($request->image, 'users');
            }

            $user->save();

            return Base::pass('Employee Profile Updated!', $user);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function ownerEmployeesProfileUpdate($request)
    {
        try {
             $authenticatedUser = Auth::user();

            if(!$authenticatedUser) return Base::fail('You are not logged in');

            $user = User::find($request->id);

            if (!isset($user)) {
                return Base::fail('User not found!');
            }
            $user->username = isset($request->username) ? $request->username : $user->username;
            $user->designation = isset($request->designation) ? $request->designation : $user->designation;
            $user->phone = isset($request->phone) ? $request->phone : $user->phone;
            $user->address = isset($request->address) ? $request->address : $user->address;
            $user->birth_date = isset($request->birth_date) ? $request->birth_date : $user->birth_date;
            if (isset($request->image)) {
                if ($user->image) {
                    if (file_exists(public_path() . $user->image)) {
                        unlink(public_path() . '/' . $user->image);
                    }
                }
                $user->image = Base::imageUpload($request->image, 'users');
            }

            $user->save();

            return Base::pass('Employee Profile Updated!', $user);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }


}
