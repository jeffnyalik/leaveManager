<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;

class ManagerController extends Controller
{
    public function managerAccess(){
        $message =  ['Success' => 'MANAGER ACCESS ONLY'];
        return response()->json($message);
    }
}
