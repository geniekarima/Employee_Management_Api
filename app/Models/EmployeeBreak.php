<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBreak extends Model
{
    use HasFactory;
    protected $fillable = ['break_start', 'break_end', 'break_duration', 'total_hours'];

    public function employeeReport()
    {
        return $this->belongsTo(EmployeeReport::class,'employee_id', 'id');
    }

}

