<?php

namespace App\Http\Controllers\leave;

use App\Http\Controllers\Controller;
use App\Models\leaves\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function createLeave(Request $request){
        $validator = Validator::make($request->all(), [
            'leave_name' => 'required',
            'leave_type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $res = new Leave();
        $res->leave_name = $request->leave_name;
        $res->leave_type = $request->leave_type;
        $res->pending = 1;
        $res->leave_status = 0;
        $res->save();
        return response()->json(['sucess' => $res], 201);
    }
    public function index(){
        $allLeaves = Leave::all();
        return response()->json($allLeaves, 200);
    }
    public function approvedLeaves(){
        $leaves = Leave::where('leave_status', '=', '1')->get();
        return response()->json(['approved' => $leaves], 200);
    }
    public function unapprovedLeaves(){
        $leaves = Leave::where('leave_status', '=', '0')->get();
        return response()->json(['unapproved' => $leaves], 200);
    }
    public function pendingLeave(){
        $res = Leave::where('pending', '=', '1')->get();
        return response()->json(['pending', $res], 200);
    }
}
