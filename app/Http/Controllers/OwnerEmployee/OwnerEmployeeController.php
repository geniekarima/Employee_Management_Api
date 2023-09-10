<?php

namespace App\Http\Controllers\OwnerEmployee;

use App\Http\Controllers\Controller;
use App\Services\Interface\OwnerEmployeeInterface;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Traits\Base;
use Exception;

class OwnerEmployeeController extends Controller
{
    private $ownerEmployeeRepository;

    public function __construct(OwnerEmployeeInterface $ownerEmployeeRepository)
    {
        $this->ownerEmployeeRepository = $ownerEmployeeRepository;
    }

    public function employeeList(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->employeeList($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function checkIn(Request $request)
    {
        try {
            $checkIn = $this->ownerEmployeeRepository->checkIn($request, request()->header('app_role'));
            return $checkIn->success ? Base::success($checkIn->message, $checkIn->data) : Base::error($checkIn->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function checkOut(Request $request)
    {
        try {
            $checkIn = $this->ownerEmployeeRepository->checkOut($request, request()->header('app_role'));
            return $checkIn->success ? Base::success($checkIn->message, $checkIn->data) : Base::error($checkIn->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function startBreak(Request $request)
    {
        try {
            $break = $this->ownerEmployeeRepository->startBreak($request, request()->header('app_role'));
            return $break->success ? Base::success($break->message, $break->data) : Base::error($break->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function endBreak(Request $request)
    {
        try {
            $break = $this->ownerEmployeeRepository->endBreak($request, request()->header('app_role'));
            return $break->success ? Base::success($break->message, $break->data) : Base::error($break->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }

    public function employeeReportList(Request $request)
    {
        try {
            $reports = $this->ownerEmployeeRepository->employeeReportList($request, request()->header('app_role'));
            return $reports->success ? Base::success($reports->message, $reports->data) : Base::error($reports->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function addTask(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->addTask($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function taskList(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->taskList($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function authTaskList(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->authTaskList($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function authProjectList(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->authProjectList($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function authTaskUpdate(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->authTaskUpdate($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function authTaskDelete(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->authTaskDelete($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function addProject(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->addProject($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function projectList(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->projectList($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function updateProject(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->updateProject($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function deleteProject(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->deleteProject($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    //assign project
    public function projectAssignAdd(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->projectAssignAdd($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function projectAssignList(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->projectAssignList($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function projectAssignUpdate(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->projectAssignUpdate($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function projectAssignDelete(Request $request)
    {
        try {
            $data = $this->ownerEmployeeRepository->projectAssignDelete($request, request()->header('app_role'));
            return $data->success ? Base::success($data->message, $data->data) : Base::error($data->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}
