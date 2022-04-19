<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function role(){
        return $this->belongsToMany(Role::class,'employee_role_project')->withTimestamps()->withPivot('project_id');
    }

    // public function rolePRojectID($employee_id,$project_id){
    //     return DB::table('employee_role_project')->where('employee_id', $employee_id)->where('project_id',$project_id)->orderby('created_at')->get();
    // }
    
    public function project()
    {
        return $this->hasMany(Project::class);
    }
}
