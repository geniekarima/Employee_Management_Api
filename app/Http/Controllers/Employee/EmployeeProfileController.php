<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Interface\EmployeeProfileInterface;
use App\Traits\Base;
use Exception;
use Illuminate\Http\Request;

class EmployeeProfileController extends Controller
{
    use Base;
    private $employeeProfileInterface;
    public function __construct(EmployeeProfileInterface $userProfileInterface)
    {
        $this->employeeProfileInterface = $userProfileInterface;
    }
    public function updateEmployeeProfile(Request $request)
    {
        try {
            $data = $this->employeeProfileInterface->updateEmployeeProfile($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function ownerEmployeesProfileUpdate(Request $request)
    {
        try {
            $data = $this->employeeProfileInterface->ownerEmployeesProfileUpdate($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}
