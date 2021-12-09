<?php

namespace App\Http\Controllers\roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(){
        $roles = Role::all();
        $message = ['success' => $roles];
        return response()->json($message, 200);
    }

    public function createRoles(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'permission' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permission);
        return response()->json($role, 201);

    }
}
