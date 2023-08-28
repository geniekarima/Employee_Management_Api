<?php

namespace App\Http\Resources;

use App\Models\EmployeeReport;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Base;


class EmployeeReportResource extends JsonResource
{
    use Base;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     */
    // protected function parseBreaks($breakTasks)
    // {
    //     $parsedBreaks = [];

    //     foreach ($breakTasks as $breakTask) {
    //         $parsedBreaks[] = [
    //             'break_start' => !empty($breakTask->break_start) ? Base::timeParse($breakTask->break_start) : null,
    //             'break_end' => !empty($breakTask->break_end) ? Base::timeParse($breakTask->break_end) : null,
    //             'break_duration' => !empty($breakTask->break_start) && !empty($breakTask->break_end)
    //             ? Base::convertDateTime($breakTask->break_start, $breakTask->break_end)
    //             : null,
    //         ];
    //     }

    //     return $parsedBreaks;
    // }
    public function toArray(Request $request)
    {
        // $data = [
        //     'date' => $this->date,
        //     'username' => $this->user->username,
        //     'check_in' => !empty($this->check_in) ? Base::timeParse($this->check_in) : null,
        //     'check_out' => !empty($this->check_out) ? Base::timeParse($this->check_out) : null,
        //     'net_work_hours' => $this->netWorkHours,
        //     'total_office_hours' => ($this->check_in != null && $this->check_out != null) ? Base::convertDateTime($this->check_in, $this->check_out) : null,
        // ];
        // if ($this->breakTasks->isNotEmpty()) {
        //     $data['break_tasks'] = $this->breakTasks->map(function ($br_report) {
        //         return [
        //             'break_start' => $br_report->break_start,
        //             'break_end' => $br_report->break_end,
        //             'break_duration' => ($br_report->break_start !== null && $br_report->break_end !== null) ? Base::convertDateTime($br_report->break_start, $br_report->break_end) : null,
        //         ];
        //     });
        // }

        // return $data;

        return [
            'date' => $this->date,
            'username' => $this->user->username,
            'check_in' => !empty($this->check_in) ? Base::timeParse($this->check_in) : null,
            'check_out' => !empty($this->check_out) ? Base::timeParse($this->check_out) : null,
            //  'break_start' => $this->breakTasks->isNotEmpty() ? Base::timeParse($this->breakTasks->break_start) : null,
             //'break_start' => $this->breakTasks->isNotEmpty() ? Base::timeParse($this->breakTasks->first()->break_start) : null,
            // 'breaks' => $this->breakTasks->isNotEmpty() ? $this->parseBreaks($this->breakTasks) : null,
            'net_work_hours' => $this->netWorkHours,
            'total_office_hours' => ($this->check_in != null && $this->check_out != null) ? Base::convertDateTime($this->check_in, $this->check_out) : null,
        ];
    }

}
