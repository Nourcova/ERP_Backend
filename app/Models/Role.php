<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function employee(){
        return $this->belongsToMany(Employee::class,'employee_role_project')->withTimestamps();
    } 
    public function project(){
        return $this->belongsToMany(Project::class,'employee_role_project')->withTimestamps();
    }
}
