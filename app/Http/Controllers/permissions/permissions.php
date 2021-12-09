<?php

namespace App\Http\Controllers\permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class permissions extends Controller
{
    public function createPermission(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:permissions'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $permission = Permission::create([
            'name' => $request->name,
        ]);

        return response()->json(['success' => $permission]);
    }

    /**Assigning permissions to role */
    public function assignPermission(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission);
        return response()->json(['success' => $role]);
    }

    /**Show roles and permissions */
    public function ShowRolesPermissions($id){
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)
        ->get();
        return response()->json(['role' => $role, 'permission'=>$rolePermissions]);
    }


}
