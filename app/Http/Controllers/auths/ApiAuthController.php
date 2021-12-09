<?php

namespace App\Http\Controllers\auths;

use App\Http\Controllers\Controller;
use App\Models\admin\Admin;
use App\Models\manager\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    /**Admin register and login */
    public function adminRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $adminUser = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => $adminUser], 201);
    }

    public function managerRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:managers',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $manager = Manager::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => $manager], 201);
    }

    public function managerLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if(auth()->guard('manager')->attempt([
            'email' => request('email'),
            'password' => request('password'),
        ])){
            config(['auth.guards.api.provider' => 'manager']);
            $manager = Manager::select('managers.*')->find(auth()->guard('manager')->user()->id);
            $success = $manager;
            $success['token'] = $manager->createToken('managerApp', ['manager'])->accessToken;
            return response()->json($success, 201);
        }else{
            return response()->json(['errors' => 'Invalid email or password']);
        }
    }

    public function adminLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()]);
        }

        if(auth()->guard('admin')->attempt([
            'email' => request('email'),
            'password' => request('password'),
        ])){
            config(['auth.guards.api.provider' => 'admin']);
            $admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
            $success = $admin;
            $success['token'] = $admin->createToken('myApp', ['admin'])->accessToken;
            return response()->json($success, 201);
        }else{
            return response()->json(['errors' => 'Invalid email or password']);
        }
    }
    /**end  */

    /**Regular user login and register functions */
    public function userRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $users = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => $users], 201);
    }

    public function userLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $user = User::where('email',$request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('Password grant token')->accessToken;
                $response = ['token' => $token];
                return response()->json($response, 201);
            }else{
                $response = ['message' => 'Invalid email or password'];
                return response()->json($response, 422);
            }
        }else{
            $message = ['message' => 'User does not exist'];
            return response()->json($message, 422);
        }
    }
}
