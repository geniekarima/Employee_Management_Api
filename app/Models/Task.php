<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'project_id',
        'title',
        'description',
        'dependency',
        'delay_reason',
        'start_date',
        'end_date',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
