<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function users(){
        $users =  User::all();
        return response()->json(['users' => $users], 200);
    }

    public function getUser($id){
        try {
            $user = User::FindOrFail($id);
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'user not found'], 404);
        }
    }

    public function updateUser(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            // 'password' => 'required',
            "roles" => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole($request->roles);
        return $user;
    }

    public function addUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            "roles" => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => $request->input('roles')
        ]);

        $user->assignRole($request->input('roles'));

        $message = ['success' => 'User has been created successfully'];
        return response()->json($message, 201);
    }

    public function addPermissionRoles(Request $request, $id){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $role = Role::findOrFail($id);
            $role->givePermissionTo($request->name);
            return response()->json($role);
    }
}
