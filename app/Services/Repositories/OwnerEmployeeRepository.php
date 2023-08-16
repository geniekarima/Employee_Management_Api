<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Interface\OwnerEmployeeInterface;
use App\Models\EmployeeReport;


use Carbon\Carbon;

class OwnerEmployeeRepository implements OwnerEmployeeInterface
{
    use Base;

    public function employeeList()
    {
        try {
            $data = User::where('usertype', 'employee')
                ->orderBy('created_at', 'desc')
                ->get();

            return Base::pass('All Employees List', $data);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function checkIn(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->format('Y-m-d');

            // Check if there's a check-in record for the current day
            $employeeCheckin = EmployeeReport::where('employee_id', $user->id)
                ->whereDate('date', $today)
                ->first();

            if ($employeeCheckin !== null) {
                return Base::fail('Already checked in today');
            }

            // Check if there's a pending check-out
            $employeeCheckout = EmployeeReport::where('employee_id', $user->id)
                ->whereDate('check_out', null)
                ->first();

            if ($employeeCheckout !== null) {
                return Base::fail('Please check out first before checking in again');
            }
            $checkIn = new EmployeeReport();
            $employeeId = auth()->user()->id;
            $checkIn->employee_id = $employeeId;
            $checkIn->check_in = Base::now();
            $checkIn->date = date('Y-m-d');

            $startTime = Carbon::parse($checkIn->check_in);
            $endTime = Carbon::parse(date("H:i:s"));
            $hoursDifference = $endTime->diffInHours($startTime);
            $checkIn->office_hours = $hoursDifference;
            $checkIn->save();

            return Base::pass('Check In Successfully', $checkIn);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function checkOut(Request $request)
    {
        try {
            $user = Auth::user();

            $employeeCheckin = EmployeeReport::where('employee_id', $user->id)
                ->whereNull('check_out')
                ->latest()
                ->first();

            if (!$employeeCheckin) {
                return Base::fail('No previous check-in found');
            }

            $employeeCheckin->check_out = Base::now();
            $employeeCheckin->save();
            return Base::pass('Check out Successfully', $employeeCheckin);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function employeeReportList(Request $request)
    {
        try {
            $fromDate = $request->input('fromdate');
            $toDate = $request->input('todate');

            // $reports = EmployeeReport::with('user');
            // $msg = "";
            // if (!empty($fromDate) && !empty($toDate)) {
            //     $fromDate = date("Y-m-d", strtotime($fromDate));
            //     $toDate = date("Y-m-d", strtotime($toDate));
            //     $reports = $reports->whereDate('date', '>=', $fromDate);
            //     $reports = $reports->whereDate('date', '<=', $toDate);
            //     $msg = ' from ' . $fromDate . ' to ' . $toDate;
            // } elseif (!empty($fromDate)) {
            //     $reports = $reports->whereDate('date', '>=', $fromDate);
            //     $msg = ' from ' . $fromDate;
            // } else {
            //     $toDate = date("Y-m-d");
            //     $reports = $reports->whereDate('date', $toDate);
            //     $msg = ' for ' . $toDate;
            // }

            $username = "";
            // // For individual employee report list
            if (!empty($request->employee_id)) {
                // $reports = $reports->where('employee_id', $request->employee_id);

                //showing employee name in blade file
                $username = User::where('id', $request->employee_id)->value('username');
            }
            // $reports = $reports->latest()
            //     ->get();

            $reports = EmployeeReport::with('user')
            ->when(isset($request->fromdate), function($q) use ($request){
                return $q->where('date', '>=', $request->fromdate);
            })
            ->when(isset($request->todate), function($q) use ($request){
                return $q->where('date', '<=', $request->todate);
            })
            ->when(isset($request->employee_id), function($q) use ($request){
                return $q->where('employee_id', $request->employee_id);
            })
            ->latest()
            ->get();

            if ($reports->isEmpty()) {
                return Base::fail('No reports found for this date');
            }
            $allData = null;
            if($request->is_pdf == 1){
                $pdf = PDF::loadView('employee_report', [
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                    'reports' => $reports,
                    'username' => $username,
                ]);

                $filename = 'employees_report_' . date('YmdHis') . '.pdf';

                $pdfPath = public_path('pdfs/' . $filename);
                $pdf->save($pdfPath);

                $pdfUrl = asset('pdfs/' . $filename);
                $allData['pdf_url'] = $pdfUrl;
            }
            $allData['reports'] = $reports;
            // Generate PDF

            // $allData = [
            //     'pdf_url' => $pdfUrl,
            //     'reports' => $reports
            // ];
            $msg = '';

            return Base::pass('Employee Report List' . $msg . ' and PDF available for download', $allData);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }








}
