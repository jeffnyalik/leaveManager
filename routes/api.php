<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\auths\ApiAuthController;
use App\Http\Controllers\leave\LeaveController;
use App\Http\Controllers\manager\ManagerController;
use App\Http\Controllers\permissions\permissions;
use App\Http\Controllers\roles\RolesController;
use App\Http\Controllers\users\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//api/leaves/approved-leaves
Route::prefix('leaves')->group(function(){
    Route::get('/', [LeaveController::class, 'index'])->name('all-leaves');
    Route::get('/approved-leaves', [LeaveController::class, 'approvedLeaves'])->name('approved-leaves');
    Route::get('/unapproved', [LeaveController::class, 'unapprovedLeaves'])->name('unapproved');
    Route::post('/create-leave', [LeaveController::class, 'createLeave'])->name('create-leave');
    Route::get('/pending-leaves', [LeaveController::class, 'pendingLeave'])->name('pending-leaves');

});

Route::prefix('admin')->group(function(){
    Route::post('approve/{id}', [AdminController::class, 'approve'])->name('approve');
    Route::post('reject/{id}', [AdminController::class, 'reject'])->name('reject');

    //Role route
    Route::get('/roles', [RolesController::class, 'index'])->name('roles')->middleware(['auth:admin-api','scopes:admin'])->name('roles');
    Route::post('/create-role', [RolesController::class, 'createRoles'])->name('roles')->middleware(['auth:admin-api','scopes:admin'])->name('create-role');

    //Permission route
    Route::post('/create-perm', [permissions::class, 'createPermission'])->name('create-perm')->middleware(['auth:admin-api','scopes:admin'])->name('create-perm');

    Route::post('/asign-perm', [permissions::class, 'assignPermission'])->name('assign-perm')->middleware(['auth:admin-api','scopes:admin'])->name('assign-perm');

    Route::get('/show-roleperm/{id}', [permissions::class, 'ShowRolesPermissions'])->name('assign-perm')->middleware(['auth:admin-api','scopes:admin'])->name('show-roleperm');

    //end route


    //Users route
    Route::get('/users', [UserController::class, 'users'])->middleware(['auth:admin-api', 'scopes:admin'])->name('users');
    Route::get('/get-user/{id}', [UserController::class, 'getUser'])->middleware(['auth:admin-api', 'scopes:admin'])->name('add-user');
    Route::post('/add-user', [UserController::class, 'addUser'])
    ->middleware(['auth:admin-api', 'scopes:admin'])->name('add-user');
    Route::post('/update-user/{id}', [UserController::class, 'updateUser'])->middleware(['auth:admin-api', 'scopes:admin'])->name('update-user');
});

// Manager access routes
Route::prefix('manager')->group(function(){
    Route::get('/manager-access', [ManagerController::class, 'managerAccess']) ->middleware('auth:api', 'scopes:manager')->name('manager-access');
});
//end of manager access routes


Route::prefix('access')->group(function(){
    Route::post('/admin-register', [ApiAuthController::class, 'adminRegister'])->name('admin-register');
    Route::post('/admin-login', [ApiAuthController::class, 'adminLogin'])->name('admin-login');


    // Manager authentication
    Route::post('/manager-register', [ApiAuthController::class, 'managerRegister'])->name('manager-register');
    Route::post('/manager-login', [ApiAuthController::class, 'managerLogin'])->name('manager-login');


    //Regular users authentication
    Route::post('/user-register', [ApiAuthController::class, 'userRegister'])->name('user-register');
    Route::post('/user-login', [ApiAuthController::class, 'userLogin'])->name('user-login');
});
