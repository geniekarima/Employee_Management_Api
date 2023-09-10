<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'title' => $this->title,
            'project_name' => $this->project->name,
            'description' => $this->description,
            'dependency' => $this->dependency,
            'delay_reason' => $this->delay_reason,
            'start_date' => $this->start_date,
            'status' => $this->status,
            'end_date' => $this->end_date,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
