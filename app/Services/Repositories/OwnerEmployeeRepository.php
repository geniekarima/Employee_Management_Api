<?php

namespace App\Services\Repositories;

use Exception;
use App\Models\User;
use App\Traits\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Interface\OwnerEmployeeInterface;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\AssignProjectResource;
use App\Http\Resources\EmployeeReportResource;
use App\Http\Resources\EmployeeResource;
use App\Models\EmployeeBreak;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendpdfNotification;
use App\Models\EmployeeReport;
use App\Models\ProjectAssign;
use Carbon\Carbon;

class OwnerEmployeeRepository implements OwnerEmployeeInterface
{
    use Base;

    public function employeeList($request)
    {
        try {
            $take = isset($request['take']) ? $request['take'] : 10;
            $employees = User::where('usertype', 'employee')
                ->orderBy('created_at', 'desc')
                ->paginate($take);

            $data = EmployeeResource::collection($employees)->response()->getData(true);

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
            $checkIn->save();
            return Base::pass('Check In Successfully', $checkIn);


        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function startBreak(Request $request)
    {
        try {
            $user = Auth::user();

            $employeeCheckin = EmployeeReport::where('employee_id', $user->id)
                ->whereNull('check_out')
                ->latest()
                ->first();

            if (!$employeeCheckin) {
                return Base::fail('No check-in found');
            }
            if (EmployeeBreak::where('employee_report_id', $employeeCheckin->id)->whereNull('break_end')->exists()) {
                return Base::fail('Break has already been started');
            }

            $break = new EmployeeBreak();
            $break->employee_report_id = $employeeCheckin->id;
            $break->break_start = Base::now();
            $break->save();

            return Base::pass('Break started Successfully', $break);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function endBreak(Request $request)
    {
        try {
            $user = Auth::user();

            $employeeCheckin = EmployeeReport::where('employee_id', $user->id)
                ->whereNull('check_out')
                ->latest()
                ->first();

            if (!$employeeCheckin) {
                return Base::fail('No check-in found');
            }
            $break = EmployeeBreak::where('employee_report_id', $employeeCheckin->id)->whereNull('break_end')
                ->latest()->first();

            if (!$break) {
                return Base::fail('No break found or break has already been ended');
            }
            $break->break_end = Base::now();

            $break->save();
            return Base::pass('Break Ended Successfully', $break);

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

            if (EmployeeBreak::where('employee_report_id', $employeeCheckin->id)->whereNull('break_end')->exists()) {
                return Base::fail('Cannot check out while breaks are ongoing');
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
            $take = isset($request['take']) ? $request['take'] : 20;
            $fromDate = $request->input('fromdate');
            $toDate = $request->input('todate');

            $sortDirection = $request->input('sort_direction', 'desc');
            $sortBy = $request->input('sort_by', 'date');

            $reports = EmployeeReport::with('user', 'breakTasks')
                ->when(isset($request->fromdate), function ($q) use ($request) {
                    return $q->where('date', '>=', $request->fromdate);
                })
                ->when(isset($request->todate), function ($q) use ($request) {
                    return $q->where('date', '<=', $request->todate);
                })
                ->when(isset($request->employee_id), function ($q) use ($request) {
                    return $q->where('employee_id', $request->employee_id);
                })
                ->when($sortBy === 'date', function ($q) use ($sortDirection) {
                    return $q->orderBy('date', $sortDirection);
                })
                ->when($sortBy === 'username', function ($q) use ($sortDirection) {
                    return $q->join('users', 'employee_reports.employee_id', '=', 'users.id')
                        ->orderBy('users.username', $sortDirection);
                })
                ->paginate($take);

            if ($reports->isEmpty()) {
                return Base::fail('No reports found.');
            }

            $username = "";
            if (!empty($request->employee_id)) {
                $username = User::where('id', $request->employee_id)->value('username');
            }
            $allData = null;
            if ($request->is_pdf == 1) {
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

                $email = $request->email;

                Notification::route('mail', $email)->notify(new SendpdfNotification($pdfPath));

                $allData['pdf_url'] = $pdfUrl;

            }
            $reportResource = EmployeeReportResource::collection($reports)->response()->getData(true);
            // $allData['reportResource'] = $reports;
            $allData['reportResource'] = $reportResource;

            return Base::pass('Employee Report List and PDF available for download', $allData);

        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function authProjectList(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user)
                return Base::fail('You are not logged in');

            $assignedProjects = ProjectAssign::where('employee_id', $user->id)
                ->with('project')
                ->get();

            $projects = $assignedProjects->pluck('project');


            return Base::pass('Your Assigned project list', $projects);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function authTaskList(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return Base::fail('You are not logged in');
            }

            // $data = Task::where('employee_id', $user->id)
            // ->orderBy('created_at', 'desc')
            // ->get();

            $tasks = User::with('projects.tasks')->where('id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // $assignments = User::with(['projects.tasks' => function ($query) {
            //     $query->whereColumn('tasks.employee_id', 'users.id'); // Replace 'user_id' with the correct column name
            // }])
            // ->orderBy('created_at', 'desc')
            // ->get();


            return Base::pass('Your Task List', $tasks);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function authTaskUpdate(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return Base::fail('You are not logged in');
            }

            $task = Task::findOrFail($request->id);

            if (!$user->projects->contains($task->project_id)) {
                return Base::fail('Task not found or not assigned to you');
            }
            $projectExists = Project::where('id', $request->input('project_id'))->exists();

            if (!$projectExists) {
                return Base::fail('Invalid project ID provided');
            }

            $task->update($request->all());

            return Base::pass('Task updated successfully', $task);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function authTaskDelete(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return Base::fail('You are not logged in');
            }

            $task = Task::find($request->id);

            if (!$task) {
                return Base::fail('Task not found');
            }

            $task->delete();

            return Base::pass('Task deleted successfully');
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

    public function addTask(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user)
                return Base::fail('You are not logged in');

            $projectAssignment = ProjectAssign::where('employee_id', $user->id)
                ->where('project_id', $request->project_id)
                ->first();

            if (!$projectAssignment) {
                return Base::fail('You are not assigned to this project');
            }

            $task = Task::create([
                'employee_id' => $user->id,
                'project_id' => $request->project_id,
                'title' => $request->title,
                'description' => $request->description,
                'dependency' => $request->dependency,
                'delay_reason' => $request->delay_reason,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
            ]);

            // $task = new TaskResource($task);
            return Base::pass('Task created successfully', $task);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function taskList($request)
    {
        try {
            $user = Auth::user();

            if (!$user)
                return Base::fail('You are not logged in');

            $tasks = User::with('projects.tasks')
                ->orderBy('created_at', 'desc')
                ->get();

            // $data = Task::orderBy('created_at', 'desc')->get();
            return Base::pass('All Employees Task List', $tasks);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function addProject(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user)
                return Base::fail('You are not logged in');

            $data = Project::create([
                'name' => $request->name,
            ]);
            return Base::pass('Project created successfully', $data);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function updateProject($request)
    {
        try {
            $authenticatedUser = Auth::user();

            if (!$authenticatedUser)
                return Base::fail('You are not logged in');

            $project = Project::find($request->id);

            if (!isset($project)) {
                return Base::fail('Project not found!');
            }
            $project->name = isset($request->name) ? $request->name : $project->name;
            $project->status = isset($request->status) ? $request->status : $project->status;
            $project->save();

            return Base::pass('Project name Updated!', $project);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }

    }
    public function deleteProject($request)
    {
        try {
            $project = Project::find($request->id);
            if (!isset($project))
                return Base::fail('Project not found');
            $project->delete();

            return Base::pass('Project Deleted Successfully');
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function projectList($request)
    {
        try {
            $data = Project::all();
            return Base::pass('All Project List', $data);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function projectAssignAdd(Request $request)
    {
        try {
            $authuser = Auth::user();
            if (!$authuser) {
                return Base::fail('You are not logged in');
            }

            $project = Project::find($request->project_id);
            if (!$project) {
                return Base::fail('Project not found');
            }

            $user = User::find($request->employee_id);
            if (!$user) {
                return Base::fail('User not found');
            }

            $projectassign = ProjectAssign::where('employee_id', $user->id)
                ->where('project_id', $project->id)
                ->first();

            if ($projectassign) {
                return Base::fail('Project already assigned to this user');
            }

            $newAssignment = new ProjectAssign();
            $newAssignment->employee_id = $user->id;
            $newAssignment->project_id = $project->id;
            $newAssignment->save();

            return Base::pass('Project assigned successfully', $newAssignment);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function projectAssignList(Request $request)
    {
        try {
            $authuser = Auth::user();
            if (!$authuser) {
                return Base::fail('You are not logged in');
            }

            // $assignments = ProjectAssign::with(['user', 'project'])
            //     ->orderBy('created_at', 'desc')
            //     ->get();

            $assignments = User::with('projects')->where('usertype', 'employee')
                ->orderBy('created_at', 'desc')
                ->get();

            // $data = new AssignProjectResource($assignments);

            return Base::pass('Project assignments retrieved successfully', $assignments);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    public function projectAssignUpdate(Request $request)
    {
        try {
            $authuser = Auth::user();
            if (!$authuser) {
                return Base::fail('You are not logged in');
            }
            $assignment = ProjectAssign::find($request->id);

            if (!$assignment) {
                return Base::fail('Project assignment not found');
            }

            $user = User::find($request->employee_id);
            if (!$user) {
                return Base::fail('User not found');
            }

            $project = Project::find($request->project_id);
            if (!$project) {
                return Base::fail('Project not found');
            }

            $assignment->employee_id = $user->id;
            $assignment->project_id = $project->id;
            $assignment->save();

            return Base::pass('Project assignment updated successfully', $assignment);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
    // public function projectAssignDelete(Request $request)
    // {
    //     try {
    //         $authuser = Auth::user();
    //         if (!$authuser) {
    //             return Base::fail('You are not logged in');
    //         }

    //         $assignment = ProjectAssign::find($request->id);

    //         if (!$assignment) {
    //             return Base::fail('Project assignment not found');
    //         }

    //         $assignment->delete();

    //         return Base::pass('Project assignment deleted successfully');
    //     } catch (Exception $e) {
    //         return Base::exception_fail($e);
    //     }
    // }
    public function projectAssignDelete(Request $request)
    {
        try {
            $authuser = Auth::user();
            if (!$authuser) {
                return Base::fail('You are not logged in');
            }

            $employee_id = $request->input('employee_id');
            $project_id = $request->input('project_id');


            $assignment = ProjectAssign::where('employee_id', $employee_id)
                ->where('project_id', $project_id)
                ->first();

            if (!$assignment) {
                return Base::fail('Project assignment not found');
            }

            $assignment->delete();

            return Base::pass('Project assignment deleted successfully');
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }

}
