<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
    public function assignProjects()
    {
        return $this->hasMany(ProjectAssign::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_assigns'); 
    }
}
