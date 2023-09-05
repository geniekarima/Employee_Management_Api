<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'project_id',
        'description',
        'dependency',
        'delay_reason',
        'start_date',
        'end_date',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
