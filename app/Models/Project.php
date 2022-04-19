<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function employee()
    {
        return $this->belongsToMany(Employee::class, 'employee_role_project')->withTimestamps()->withPivot('id', 'role_id');
    }

    // public function role()
    // {
    //     return $this->belongsToMany(Role::class, 'employee_role_project')->withTimestamps();
    // }

}
