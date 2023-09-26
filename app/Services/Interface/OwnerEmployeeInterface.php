<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface OwnerEmployeeInterface
{
    public function employeeList(Request $request);
    public function employeeReportList(Request $request);
    public function checkIn(Request $request);
    public function startBreak(Request $request);
    public function endBreak(Request $request);
    public function checkOut(Request $request);
    public function addTask(Request $request);
    public function authTaskList(Request $request);
    public function authTaskUpdate(Request $request);
    public function authTaskDelete(Request $request);
    public function taskList(Request $request);
    public function addProject(Request $request);
    public function authProjectList(Request $request);
    public function deleteProject(Request $request);
    public function updateProject(Request $request);
    public function projectList(Request $request);
    public function projectAssignAdd(Request $request);
    public function projectAssignList(Request $request);
    public function projectAssignUpdate(Request $request);
    public function projectAssignDelete(Request $request);

}
