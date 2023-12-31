<?php

namespace App\Http\Resources;
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
    public function toArray(Request $request)
    {
        return [
            'date' => $this->date,
            'username' => $this->user->username,
            'check_in' => !empty($this->check_in) ? $this->check_in : null,
            'check_out' => !empty($this->check_out) ? $this->check_out : null,
            'breakTasks' => $this->breakTasks,
            'net_work_hours' => $this->netWorkHours,
            'total_office_hours' =>  Base::convertDateTime($this->check_in, $this->check_out),
        ];
    }

}
