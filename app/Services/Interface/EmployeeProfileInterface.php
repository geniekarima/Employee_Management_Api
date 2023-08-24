<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface EmployeeProfileInterface
{
     public function updateEmployeeProfile(Request $request);
     public function ownerEmployeesProfileShow(Request $request);
     public function ownerEmployeesProfileUpdate(Request $request);
     public function ownerEmployeesProfileDeactivate(Request $request);
     public function ownerDeleteEmployeesProfile(Request $request);

}
