<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'username' => $this->user->username,
            'date' => $this->date,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
        ];
    }
}
