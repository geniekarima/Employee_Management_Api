<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReport extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'check_in', 'check_out', 'office_hours'];

    public function user(){
        return $this->belongsTo(User::class, 'employee_id', 'id');

    }
}
