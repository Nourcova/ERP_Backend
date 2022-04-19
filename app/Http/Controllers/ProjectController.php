<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    function getAll()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $project->team;
            $project->employee;
            $employees = $project->employee;
            foreach ($employees as $employee) {
                $employee->pivot['role_id']= Role::where('id',$employee->pivot['role_id'])->first();
            }
        }
        $responde = [
            'status' => 200,
            'message' => 'Projects',
            'data' => $projects
        ];
        return $responde;
    }


    function get($id)
    {
        $project = Project::find($id);
        $project->team;
        $project->employee;
        $employees = $project->employee;

        if (!isset($project)) {
            $respond = [
                'status' => 401,
                'message' => "Project of id=$id doesn't exist",
                'data' => $project,
            ];
            return $respond;
        }

        $project->team;
        $project->employee;
        $employees = $project->employee;
        foreach ($employees as $employee) {
            $employee->pivot['role_id'] = Role::where('id', $employee->pivot['role_id'])->first();
        }

        $respond = [
            'status' => 200,
            'message' => "Project of id $id",
            'data' => $project,
        ];
        return $respond;
    }


    function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'in_progress' => 'required',
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' =>  $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        }

        $new_project = new Project;
        $new_project->name = $request->name;
        $new_project->in_progress = $request->in_progress;
        $new_project->team_id = $request->team_id;
        $new_project->save();

        $respond = [
            'status' => 200,
            'message' => "Project successfully created",
            'data' => $new_project,
        ];
        return $respond;
    }

    function delete($id)
    {
        $project = Project::find($id);

        if (isset($project)) {
            $check = empty($project->team);
            if ($check) {
                $project->delete();
                $project = Project::all();
                $respond = [
                    'status' => '200',
                    'message' => 'Project Successflully deleted !!',
                    'data' => $project,
                ];
                return $respond;
            }
            
            $respond = [
                'status' => 400,
                'message' => 'This Project has Team(s) ,CAN NOT be deleted  !',
                'data' => $project->team
            ];

            return $respond;
        } else {
            $respond = [
                'status' => 500,
                'message' => "Project with id $id Not found",
                'data' => null,
            ];
        }
        return $respond;
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (isset($project)) {
            $project->name = $request->name;
            $project->in_progress = $request->in_progress;
            $project->save();

            $respond = [
                'status' => 200,
                'message' =>  "project updated successfully",
                'data' => $project,
            ];
            return $respond;
        }

        $respond = [
            'status' => 401,
            'message' =>  "project with id=$id doesn't exist",
            'data' => null,
        ];
        return $respond;
    }
    //

    public function addRemoveProjectTeam(Request $request, $id)
    {
        // $team = Team::find($id);
        $proj = Project::find($id);

        if (isset($proj)) {
            // $proj->Team;
            $proj->team_id = $request->team_id;
            $proj->save();
            $respond = [
                'status' => 200,
                'message' =>  "Project updated to team successfully",
                'data' => $proj,
            ];
            return $respond;    
        }
        $respond = [
            'status' => 401,
            'message' =>  "Project with id=$id doesn't exist",
            'data' => null,
        ];
        return $respond;
    }

    public function roleToEmployee(Request $request, $id)
    {
        $project = Project::find($id);
        $role_id = $request->role;
        $employee_id = $request->employee;

        $employee = Employee::find($employee_id);

        $employee->role()->attach($role_id, ['project_id' => $id, 'role_id' => $role_id]);
        $employee->role;

        $respond = [
            'status' => 200,
            'message' =>  "add role successfully",
            'data' => $project,
        ];
        return $respond;
    }
}