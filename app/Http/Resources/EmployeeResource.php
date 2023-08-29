<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'usertype' => $this->usertype,
            'designation' => $this->designation,
            'phone' => $this->phone,
            'address' => $this->address,
            'image' => $this->image,
            'birth_date' => $this->birth_date,
            // "is_verified" => $this->is_verified ? 1 : 0,
            "is_active" => $this->is_active ? 1 : 0,
        ];
    }
}
