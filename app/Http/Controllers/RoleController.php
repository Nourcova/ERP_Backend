<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

  public  function getAll()
    {
        $role = Role::all();

        foreach ($role as $each) {
            $each->team;
        }

        $responde = [
            'status' => 200,
            'message' => 'Roles',
            'data' => $role
        ];
        return $responde;
    }

    function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' =>  $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        }

        $new_role = new Role;
        $new_role->name = $request->name;
        $new_role->save();

        $respond = [
            'status' => 201,
            'message' => "Role successfully created",
            'data' => $new_role,
        ];
        return $respond;
    }

    function delete($id)
    {
        $role = Role::find($id);

        if (isset($role)) {
            $role->delete();
            $all_role = Role::all();

            $respond = [
                'status' => 401,
                'message' =>  "Role successfully deleted",
                'data' => $all_role,
            ];
        } else {
            $respond = [
                'status' => 401,
                'message' =>  "Role with id=$id doesn't exist",
                'data' => NUll,
            ];
        }
        return $respond;
    }
}
