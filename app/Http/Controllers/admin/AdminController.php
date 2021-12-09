<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\leaves\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function approve($id){
        $leave  = Leave::findOrFail($id);
        $leave->leave_status=1; //change the leave status to approved
        $leave->save();
        $message =  'Leave has been approved successfully';
        return response([$message, $leave], 201);
    }

    public function reject(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'reasons' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $leave  = Leave::findOrFail($id);
        $leave->leave_status=0;
        $leave->reasons = $request->reasons;
         //change the leave status to rejected
        $leave->save();
        $message =  'Your application has been rejected';
        return response([$message , $leave], 201);
    }

}
