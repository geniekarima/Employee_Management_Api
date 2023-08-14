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
    // for any specific date filter

    public function employeeReportList(Request $request)
    {
        try {
            $date = $request->input('todate');

            if (empty($date)) {
                $date = date("Y-m-d");
            } else {
                $date = date("Y-m-d", strtotime($date));
            }

            $reports = EmployeeReport::with('user')
                ->whereDate('date', $date)
                ->latest()
                ->get();
            return Base::pass('Employee Report List for ' . $date, $reports);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function employeeReportListFromDate(Request $request)
    {
        try {
            $fromDate = $request->input('fromdate');
            $toDate = $request->input('todate');

            if (empty($fromDate)) {
                $fromDate = date("Y-m-d");
            } else {
                $fromDate = date("Y-m-d", strtotime($fromDate));
            }

            if (empty($toDate)) {
                $toDate = date("Y-m-d");
            } else {
                $toDate = date("Y-m-d", strtotime($toDate));
            }

            if (strtotime($fromDate) > strtotime($toDate)) {
                $tempDate = $fromDate;
                $fromDate = $toDate;
                $toDate = $tempDate;
            }

            $reports = EmployeeReport::with('user')
                ->whereBetween('date', [$fromDate, $toDate])
                ->latest()
                ->get();

            // if ($reports->isEmpty()) {
            //     return Base::fail('No reports found for the specified date range');
            // }

            return Base::pass('Employee Report List from ' . $fromDate . ' to ' . $toDate, $reports);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }



    public function individualReportList(Request $request)
    {
        try {
            $reports = EmployeeReport::where('employee_id', $request->employee_id)->get();

            if (!$reports)
                return Base::error('Employee not found!');
            return Base::pass('Employee Report List', $reports);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    // generate pdf
    public function generatePDF(Request $request)
    {
        try {

            $fromDate = $request->input('fromdate');
            $toDate = $request->input('todate');

            if (empty($fromDate)) {
                $fromDate = date("Y-m-d");
            } else {
                $fromDate = date("Y-m-d", strtotime($fromDate));
            }

            if (empty($toDate)) {
                $toDate = date("Y-m-d");
            } else {
                $toDate = date("Y-m-d", strtotime($toDate));
            }

            if (strtotime($fromDate) > strtotime($toDate)) {
                $tempDate = $fromDate;
                $fromDate = $toDate;
                $toDate = $tempDate;
            }

            $reports = EmployeeReport::with('user')
                ->whereBetween('date', [$fromDate, $toDate])
                ->latest()
                ->get();

            if ($reports->isEmpty()) {
                return Base::fail('No reports found for the specified date range');
            }

            // Generate PDF
            $pdf = PDF::loadView('employee_report', [
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'reports' => $reports,
            ]);

            $filename = 'employees_report_' . date('YmdHis') . '.pdf';

            $pdfPath = public_path('pdfs/' . $filename);
            $pdf->save($pdfPath);

            $pdfUrl = asset('pdfs/' . $filename);

            return Base::pass('PDF generated and available for download', ['pdf_url' => $pdfUrl]);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

public function generateIndividualPDF(Request $request)
{
    try {
        $reports = EmployeeReport::where('employee_id', $request->employee_id)->get();

        if (!$reports) {
            return Base::error('Employee not found!');
        }

        $pdf = PDF::loadView('individual_report', ['reports' => $reports]);

        $filename = 'employee_report_' . $request->employee_id . '.pdf';

        $pdfPath = public_path('pdfs/' . $filename);
        $pdf->save($pdfPath);

        $pdfUrl = asset('pdfs/' . $filename);

        return Base::pass('Employee Report List pdf', ['pdf_link' => $pdfUrl]);
    } catch (Exception $e) {
        return Base::exception_fail($e);
    }
}

}
