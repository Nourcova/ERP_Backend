<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class TeamController extends Controller
{
    public function getAllTeams()
    {
        $teams = Team::all();

        foreach ($teams as $each) {
            $each->employee;
            $each->project;
        }
        $respond = [
            'status' => '200',
            'message' => 'All Teams',
            'data' => $teams,
        ];
        return $respond;
    }
    public function getTeam($id)
    {
        $teams = Team::find($id);
        $countEmp = DB::table('employees')->where('team_id', $id)->count();
        $countPro = DB::table('projects')->where('team_id', $id)->count();
        if (isset($teams)) {
            $teams->employee;
            $teams->project;
            $employees = $teams->employee;
            foreach ($employees as $each) {
                $each->role;
            }
            $respond = [
                'status' => '200',
                'message' => 'Team found',
                'Employees-Number' => $countEmp,
                'Projects-Number' => $countPro,
                'data' => $teams,
            ];
            return $respond;
        }
        $respond = [
            'status' => '401',
            'message' => 'Team not found',
            'data' => null
        ];
        return $respond;
    }

    public function getTeamWithEmployeeRole(Request $request, $id)
    {
        $teams = Team::find($id);
        $project_id = $request->id;
        $countEmp = DB::table('employees')->where('team_id', $id)->count();
        $countPro = DB::table('projects')->where('team_id', $id)->count();
        if (isset($teams)) {
            $teams->employee;
            $teams->project;
            $employees = $teams->employee;
            foreach ($employees as $each) {
                $roles = $each->rolePRojectID($each->id,$project_id);
               
                foreach($roles as $eachRole){
                    log::info(print_r($eachRole,true));
                    $roleName = Role::where('id',$eachRole->role_id)->first();
                    $eachRole->rolename = $roleName->name;
                }
                $each->roleProject = $roles;
            }
            $respond = [
                'status' => '200',
                'message' => 'Team found',
                'Employees-Number' => $countEmp,
                'Projects-Number' => $countPro,
                'data' => $teams,
            ];
            return $respond;
        }
        $respond = [
            'status' => '401',
            'message' => 'Team not found',
            'data' => null
        ];
        return $respond;
    }

    public function addTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);
        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        }
        $teams = new Team();
        $teams->name = $request->name;
        $teams->save();
        $respond = [
            'status' => '200',
            'message' => 'New Team successfully added',
            'data' => $teams
        ];
        return $respond;
    }
    public function updateTeam(Request $request, $id)
    {
        $teams = Team::find($id);
        if (isset($teams)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                $respond = [
                    'status' => 401,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ];
                return $respond;
            }

            $teams->name = $request->name;
            $teams->save();

            $respond = [
                'status' => '200',
                'message' => 'Team successfully updated',
                'data' => $teams
            ];
            return $respond;
        } else {
            $respond = [
                'status' => '401',
                'message' => 'Team not found',
                'data' => $teams
            ];
            return $respond;
        }
    }

    public function deleteTeam($id)
    {
        $team = Team::find($id);

        if (isset($team)) {
            $check = empty($team->Employee->all());
            // $check = empty($team->Employee->all());
            if ($check) {
                $team->delete();

                $teams = Team::all();

                $respond = [
                    'status' => '200',
                    'message' => 'Successflully deleted !!',
                    'data' => $teams,
                ];
                return $respond;
            }
            $respond = [
                'status' => '400',
                'message' => 'Not Empty team can not be deleted !',
                'data' => $team->Employee

            ];
            return $respond;
        } else {
            $respond = [
                'status' => 400,
                'message' => "Team with id $id Not found",
                'data' => null,
            ];
        }
        return $respond;
    }
    public function addEmployeeToTeam(Request $request, $id)
    {

        $emp = Employee::find($id);

        if (isset($emp)) {
            $emp->Team;
            $emp->team_id = $request->team_id;
            $emp->save();
            $respond = [
                'status' => 201,
                'message' =>  "Employee Added to team successfully",
                'data' => $emp,
            ];

            return $respond;
        }
        $respond = [
            'status' => 401,
            'message' =>  "Employee with id $id doesn't exist",
            'data' => null,
        ];

        return $respond;
    }
    public function employeesWithoutTeam()
    {
        $employee = DB::table('employees')->select('*')
            ->where('team_id', null)
            ->get();
        if ($employee) {
            $respond = [
                'status' => 200,
                'message' => 'Employees With No teams :',
                'data' => $employee,
            ];
            return $respond;
        }
        $respond = [
            'status' => 200,
            'message' =>  "All Employees have teams !",
            'data' => null,
        ];
    }
    
    public function employeesWithTeam()
    {
        $employee = Employee::where('team_id', '>', 0)->get();
        foreach($employee as $each){
            $each->team;
        }
            
        if ($employee) {
            $respond = [
                'status' => 200,
                'message' => 'Employees With  teams :',
                'data' => $employee,
            ];
            return $respond;
        }
        $respond = [
            'status' => 200,
            'message' =>  "---------------------- !",
            'data' => null,
        ];
    }

    public function removeProjectFromTeam($id)
    {
        $employee = DB::table('projects')->select('*')
            ->where('id', '=', $id)
            ->update(['team_id' => null]);

        if ($employee) {
            $empData = Employee::find($id);
            $respond = [
                'status' => 200,
                'message' => "You removed the project with id $id from the team",
                'data' => $empData,
            ];
            return $respond;
        } else {
            return 'ERROR';
        }
    }

    public function removeEmployeeFromTeam($id)
    {
        $employee = DB::table('employees')->select('*')
            ->where('id', '=', $id)
            ->update(['team_id' => null]);
        if ($employee) {
            $empData = Employee::find($id);
            $respond = [
                'status' => 200,
                'message' => "You removed the employee with id $id from the team",
                'data' => $empData,
            ];
            return $respond;
        } else {
            return 'ERROR';
        }
    }
}
