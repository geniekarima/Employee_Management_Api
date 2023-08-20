<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReport extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'check_in', 'check_out'];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    public function breakTasks()
    {
        return $this->hasMany(EmployeeBreak::class, 'employee_report_id', 'id');
    }

    public function getNetWorkHoursAttribute()
    {
        // $checkIn = Carbon::parse($this->check_in);
        // $checkOut = Carbon::parse($this->check_out);

        // $checkIn = Carbon::parse($this->checkIn->check_in);
        // $checkOut = Carbon::parse(date("H:i:s"));

        $checkIn = Carbon::createFromFormat('H:i:s', $this->check_in);
        $checkOut = Carbon::createFromFormat('H:i:s', $this->check_out);

        $workDurationInMinutes = CarbonInterval::minutes($checkIn->diffInMinutes($checkOut))->totalMinutes;

        $totalBreakMinutes = $this->breakTasks->sum(function ($break) {
            $breakStart = Carbon::parse($break->break_start);
            $breakEnd = Carbon::parse($break->break_end);
            return $breakStart->diffInMinutes($breakEnd);
        });

        $netWorkMinutes = $workDurationInMinutes - $totalBreakMinutes;


        $netWorkHours = floor($netWorkMinutes / 60);
        $netWorkRemainingMinutes = $netWorkMinutes % 60;

        return $netWorkHours . ':' . $netWorkRemainingMinutes;

    }

}



