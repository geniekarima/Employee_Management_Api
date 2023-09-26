<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'usertype',
        'designation',
        'phone',
        'address',
        'image',
        'birth_date',
    ];
    // public function employees()
    // {
    //     return $this->hasMany(EmployeeReport::class);
    // }
    public function employees(): HasMany
    {
        return $this->hasMany(EmployeeReport::class,);
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_assigns','employee_id', 'project_id'); 
    }

    public function tasks()
    {
        return Task::where('employee_id', $this->getAttribute('employee_id'));
    }

    // public function tasks()
    // {
    //     return $this->hasMany(Task::class, 'employee_id')->where('employee_id', $this->getAttribute('employee_id'));
    // }



    // public function projectAssigns()
    // {
    //     return $this->hasMany(ProjectAssign::class, 'employee_id');
    // }

    // public function tasksByProjectAndEmployee()
    // {
    //     return $this->hasManyThrough(Task::class, ProjectAssign::class, 'employee_id', 'project_id', 'id', 'project_id')
    //             ;
    // }

    //  public function tasks()
    // {
    //     return $this->hasManyThrough(Task::class, ProjectAssign::class, 'employee_id', 'project_id', 'id', 'project_id');
    // }

    public function assignProjects()
    {
        return $this->hasMany(AssignProject::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
