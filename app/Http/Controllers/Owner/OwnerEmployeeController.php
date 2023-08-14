<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\Interface\OwnerEmployeeInterface;
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

    public function employeeList()
    {
        try {
            $data = $this->ownerEmployeeRepository->employeeList();
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

    public function employeeReportList(Request $request)
    {
        try {
            $reports = $this->ownerEmployeeRepository->employeeReportList($request, request()->header('app_role'));
            return $reports->success ? Base::success($reports->message, $reports->data) : Base::error($reports->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function employeeReportListFromDate(Request $request)
    {
        try {
            $reports = $this->ownerEmployeeRepository->employeeReportListFromDate($request, request()->header('app_role'));
            return $reports->success ? Base::success($reports->message, $reports->data) : Base::error($reports->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function individualReportList(Request $request)
    {
        try {
            $reports = $this->ownerEmployeeRepository->individualReportList($request, request()->header('app_role'));
            return $reports->success ? Base::success($reports->message, $reports->data) : Base::error($reports->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function generatePDF(Request $request)
    {
        try {
            $reports = $this->ownerEmployeeRepository->generatePDF($request, request()->header('app_role'));
            return $reports->success ? Base::success($reports->message, $reports->data) : Base::error($reports->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function generateIndividualPDF(Request $request)
    {
        try {
            $reports = $this->ownerEmployeeRepository->generateIndividualPDF($request, request()->header('app_role'));
            return $reports->success ? Base::success($reports->message, $reports->data) : Base::error($reports->message);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
}
