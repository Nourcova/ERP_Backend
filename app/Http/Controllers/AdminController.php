<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;




class AdminController extends Controller
{
    public function getAllAdmins()
    {
        $admins = Admin::all();
        $respond = [
            'status' => '200',
            'message' => 'All Admins',
            'data' => $admins
        ];
        return $respond;
    }

    public function getAdmin($id)
    {
        $admin = Admin::find($id);
        if (isset($admin)) {
            $respond = [
                'status' => '200',
                'message' => `Admin with id $id`,
                'data' => $admin
            ];
            return $respond;
        }
        $respond = [
            'status' => '401',
            'message' => 'Admin not found',
            'data' => null
        ];
        return $respond;
    }

    public function addAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:admins',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'image' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;

        }  
        $admin = new Admin();
        $files = $request->file('image');
        $destinationPath = 'image/'; // upload path lastModified
        $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
        $files->move($destinationPath, $profileImage);
        $admin->image = $profileImage;
   
        $admin->name = $request->name;
        $admin->email = $request->email;
         $admin->password =Hash::make( $request->password);       
        $admin->save();
        $respond = [
            'status' => '200',
            'message' => 'Admin successfully added',
            'data' => $admin
        ];
        return $respond;
    }

    public function deleteAdmin($id)
    {
        $admin = Admin::find($id);
        
         if (isset($admin)) {
            $image_path = 'image/'.$admin->image;
            unlink($image_path);
            $admin->delete();
            $admins = Admin::all();

            $respond = [
                'status' => '200',
                'message' => 'Admin successflully deleted',
                'data' => $admins
            ];
            return $respond;
        }
        $respond = [
            'status' => '401',
            'message' => 'Admin not found',
            'data' => null
        ];
        return $respond;
    }
    
    public function updateAdmin(Request $request, $id)
    {
        $admin = Admin::find($id);
      
        if (isset($admin)) {
           
            $input = $request->all();
            $admin = Admin::where('id', $id)->update($input);
            
            if($files = $request->file('image')){
                $admin = Admin::find($id);
                $destinationPath = 'image/'; // upload path lastModified
                $profileImage = date('YmdHis') . "_image." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
                $admin->image = $profileImage;
                $admin->save();                   
            }
            $respond = [
                'status' => '200',
                'message' => 'Admin successfully updated',
                'data' => $admin
            ];
            return $respond;
        } else {
            $respond = [
                'status' => '401',
                'message' => 'Admin not found',
                'data' => null
            ];
            return $respond;
        }
    }

    public function updateImageAdmin(Request $request, $id)
    {
        ///
        $validator = Validator::make($request->all(), [
             
            'image' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        } 
        ///
        $admin = Admin::find($id);
      
        if (isset($admin)) {
           
            $input = $request->all();
            $admin = Admin::where('id', $id)->update($input);
            
            if($files = $request->file('image')){
                $admin = Admin::find($id);
                $destinationPath = 'image/'; // upload path lastModified
                $profileImage = date('YmdHis') . "_image." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
                $admin->image = $profileImage;
                $admin->save();                   
            }

            $respond = [
                'status' => '200',
                'message' => 'Admin Image successfully updated',
                'data' => $admin
            ];
            return $respond;
        } else {
            $respond = [
                'status' => '401',
                'message' => 'Admin not found',
                'data' => null
            ];
            return $respond;
        }
    }

    ///////////////////////////
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $respond = [
                    'status' => 400,
                    'message' => 'invalid_credentials',
                    'data' => null,
                ];
                return $respond;
             }
        } catch (JWTException $e) {
            $respond = [
                'status' => 500,
                'message' => 'could_not_create_token',
                'data' => null,
            ];
            return $respond;
         }
         $admin=Admin::where('email',$request->email)->first();
        // $cookie=cookie('jwt' , $token , 60*24);
          return response([
            'message'=>'success',
            'status'=>200,
            'admin'=>$admin,
             'data' =>response()->json(compact('token')),
         ]);
    }

    public function admin(){
        return 'Authenticated Admin!';
    }

    public function logOut(){
        auth()->logout();
        return response()->json(['message' => "Logged out successfully!"]);
    }
}
