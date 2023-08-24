<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use Illuminate\Support\Facades\Auth;
use App\Services\Interface\EmployeeProfileInterface;
use App\Http\Resources\EmployeeResource;

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
    public function ownerEmployeesProfileShow($request)
    {
        try {
            $user = User::find($request->id);

            if (!isset($user))
                return Base::fail('User not found');

            $user = new EmployeeResource($user);

            return Base::pass('Existing employee!', $user);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function ownerEmployeesProfileUpdate($request)
    {
        try {
            $authenticatedUser = Auth::user();

            if (!$authenticatedUser)
                return Base::fail('You are not logged in');

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
    public function ownerEmployeesProfileDeactivate($request)
    {
        try {
            $user = User::find($request->id);
            if (!isset($user))
                return Base::fail('User not found');


            $user->is_active = !$user->is_active;
            $user->save();
            return Base::pass('Employee Deactivated!');
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function ownerDeleteEmployeesProfile($request)
    {
        try {
            $user = User::find($request->id);
            if (!isset($user))
                return Base::fail('User not found');

            $user->delete_reason = isset($request->delete_reason) ? $request->delete_reason : $user->delete_reason;
            $user->delete();

            return Base::pass('Employee Deleted');
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}
