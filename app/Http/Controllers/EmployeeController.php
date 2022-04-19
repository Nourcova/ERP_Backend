<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    public function getAllEmployees()
    {
        $employees = Employee::all();

        foreach ($employees as $each) {
            $each->kpi;
            $each->team;
            $each->project;
            $project = $each->project;;
            foreach ($project as $each) {
                $each->role;
            }
        }
        $respond = [
            'status' => '200',
            'message' => 'All Employees',
            'data' => $employees
        ];
        return $respond;
    }

    public function getEmployeeById($id)
    {

        $employee = Employee::find($id);
        if (isset($employee)) {
            $employee->kpi;
            $employee->team;
            $employee->project;
            $project = $employee->project;
            foreach($project as $each){
                //$each->role;
                $each->pivot['role_id']= Role::where('id',$each->pivot['role_id'])->first();
            }

            $respond = [
                'status' => '200',
                'message' => 'Employee found',
                'data' => $employee
            ];
            return $respond;
        }
        $respond = [
            'status' => '401',
            'message' => 'Employee not found',
            'data' => null
        ];
        return $respond;
    }

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'image' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' =>  $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        }
        $employee = new Employee();
        $files = $request->file('image');
        $destinationPath = 'image/'; // upload path lastModified
        $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
        $files->move($destinationPath, $profileImage);
        $employee->image = $profileImage;


        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        // $employee->image = $request->image;
        $employee->team_id = $request->team_id;
        $employee->save();

        $employee->team;

        $respond = [
            'status' => 200,
            'message' => 'New employee was added Successfully !',
            'data' => $employee,
        ];
        return $respond;
    }

    public function updateEmployee(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' =>  $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        }
        
        $emp = Employee::find($id);
        $img = $emp->image;
        $rate = $request->rate;
        $kpi_id = $request->kpi_id;

        if (isset($emp)) {  
            // if($emp->kpi()->find($kpi_id)){
            //     return [
            //         'status'=>401,
            //         'message'=>'Employee already has this kpi',
            //         'data'=>null
            //     ];
            // }
            if($files = $request->file('image')){
                $emp = Employee::find($id);
                $destinationPath = 'image/'; // upload path lastModified
                $profileImage = date('YmdHis') . "_image." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
                $emp->image = $profileImage;
                // $emp->save();                   
            }
            $emp->kpi;
            $emp->team;

            $emp->first_name = $request->first_name;
            $emp->last_name = $request->last_name;
            $emp->email = $request->email;
            $emp->image = $img;
            $emp->phone = $request->phone;
            $emp->team_id = $request->team_id;
            // $emp->team->name = $request->team_name;//new 
            $emp->save();

            $emp->kpi()->attach($kpi_id, ["rate" => $rate]);
            $emp = Employee::find($id);
            $emp->kpi;

            $respond = [
                'status' => 201,
                'message' =>  "Employee updated successfully",
                'data' => $emp,
            ];

            return $respond;
        }
        $respond = [
            'status' => 401,
            'message' =>  "Employee with id=$id doesn't exist",
            'data' => null,
        ];
        return $respond;
    }

    public function deleteEmployee($id)
    {
        $emp = Employee::find($id);
        if (isset($emp)) {
            $emp->delete();
            $emps = Employee::all();

            $respond = [
                'status' => 401,
                'message' =>  "Employee successfully deleted",
                'data' => $emps,
            ];
        } else {
            $respond = [
                'status' => 401,
                'message' =>  "Employee with id=$id doesn't exist",
                'data' => null,
            ];
        }
        return $respond;
    }

    public function reportsKpisValid($id){
        $employee = Employee::find($id);
        $employee->validKpi;
        $kpi = $employee->validKpi;
        foreach($kpi as $index=>$each){
            $kpi_data = DB::table('employee_kpi')->where('employee_id',$id)->where('kpi_id',$each->id)->orderBy('created_at','desc')->get()->groupBy('kpi_id');
            $each->latest_kpi = $kpi_data->first()->first();
        }
        $respond = [
            'status' => 200,
            'message' => "Employee with valid KPIS",
            'data' => $employee,
        ];
        return $respond;
    }

    public function reportsGroupKpis($id){
        $employee = Employee::find($id);
        $employee->GroupKpi;
        $kpi = $employee->GroupKpi;
        foreach($kpi as $each){
            $kpi_data = DB::table('employee_kpi')->where('employee_id',$id)->where('kpi_id',$each->id)->orderBy('created_at','desc')->get()->groupBy('kpi_id');
            $each->kpi = $kpi_data->first();
        }
        $respond = [
            'status' => 200,
            'message' => "Employee with valid KPIS",
            'data' => $employee,
        ];
        return $respond;
    }

    public function reportsProject($id){
        $employee = Employee::find($id);
        $employee->ProjecReport;
        $kpi = $employee->ProjecReport;
        foreach($kpi as $each){
            $role_data = DB::table('employee_role_project')->where('employee_id',$id)->where('project_id',$each->id)->orderBy('created_at','desc')->get()->groupBy('project_id')->first();
            $each->role = $role_data;
            foreach($role_data as $eachRole){
                $roleName = Role::where('id',$eachRole->role_id)->first();
                $eachRole->rolename = $roleName->name;         
            }
        }
        $respond = [
            'status' => 200,
            'message' => "Employee with valid KPIS",
            'data' => $employee,
        ];
        return $respond;
    }
    
    // public function addRoleToEmployee(Request $request,$id){
    //     $employee = Employee::find($id);
    //     $role = $request->role;
    //     if(isset($employee)){
    //         $employee->role()->attach($role);
    //         $employee->role;
    //         return $employee;
    //     }
    //     return "employee doesn't exist";
    // }
}
