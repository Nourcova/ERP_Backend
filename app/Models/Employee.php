<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;

    public function kpi(){
        return $this->belongsToMany(Kpi::class)->withTimestamps()->withPivot(['rate']);
    }

    public function onekpi(){
        return $this->validKpi();
    }

    public function validKpi(){
        return $this->belongsToMany(Kpi::class)->groupBy('name');
    }

    public function GroupKpi(){
        return $this->belongsToMany(Kpi::class)->groupBy('name');
    }

    public function ProjecReport(){
        return $this->belongsToMany(Project::class, 'employee_role_project')->groupBy('name');
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function role(){
        return $this->belongsToMany(Project::class,'employee_role_project')->withTimestamps()->withPivot('role_id')->groupBy('project_id');
    }

    public function rolePRojectID($employee_id,$project_id){
        return DB::table('employee_role_project')->where('employee_id', $employee_id)->where('project_id',$project_id)->orderby('created_at', 'desc')->get();
    }

    public function project(){
        return $this->belongsToMany(Project::class,'employee_role_project')->withTimestamps()->withPivot('role_id');
    }

}
