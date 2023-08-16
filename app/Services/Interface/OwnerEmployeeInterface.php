<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface OwnerEmployeeInterface
{
    public function employeeList();
    public function employeeReportList(Request $request);
    public function checkIn(Request $request);
    public function checkOut(Request $request);
}
