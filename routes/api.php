<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.verify']], function () {

    //Teams Routes
    Route::group(['prefix' => 'teams'], function () {
        Route::get('/', [TeamController::class, 'getAllTeams']);
        Route::get('/{id}', [TeamController::class, 'getTeam']);
        Route::post('/test/{id}', [TeamController::class, 'getTeamWithEmployeeRole']);
        Route::post('/add', [TeamController::class, 'addTeam']);
        Route::delete('/{id}', [TeamController::class, 'deleteTeam']);
        Route::post('/{id}', [TeamController::class, 'updateTeam']);
        Route::post('/empToTeam/{id}', [TeamController::class, 'addEmployeeToTeam']);
        Route::get('/employeesWithout/team', [TeamController::class, 'employeesWithoutTeam']);
        Route::get('/employeesWith/team', [TeamController::class, 'employeesWithTeam']);
        Route::get('/employeesWithout/remove/{id}', [TeamController::class, 'removeEmployeeFromTeam']);
        Route::post('/project/remove/{id}', [TeamController::class, 'removeProjectFromTeam']);
    });

    //KPI Routes
    Route::group(['prefix' => 'kpi'], function () {
        Route::get('/', [KpiController::class, 'getAllKpi']);
        Route::get('/{id}', [KpiController::class, 'getKpiById']);
        Route::post('/', [KpiController::class, 'addKpi']);
        Route::delete('/{id}', [KpiController::class, 'deleteKpi']);
        Route::post('/{id}', [KpiController::class, 'updateKpi']);
    });

    //Employees Routes
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', [EmployeeController::class, 'getAllEmployees']);
        Route::post('/', [EmployeeController::class, 'addEmployee']);
        Route::get('/{id}', [EmployeeController::class, 'getEmployeeById']);
        Route::delete('/{id}', [EmployeeController::class, 'deleteEmployee']);
        Route::post('/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::post('/role/{id}', [EmployeeController::class, 'addRoleToEmployee']);
        Route::get('/validKPIS/{id}', [EmployeeController::class, 'reportsKpisValid']);
        Route::get('/groupKpi/{id}', [EmployeeController::class, 'reportsGroupKpis']);
        Route::get('/reportProject/{id}', [EmployeeController::class, 'reportsProject']);
    });


    //Projects Route
    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectController::class, 'getAll']);
        Route::get('/{id}', [ProjectController::class, 'get']);
        Route::post('/', [ProjectController::class, 'add']);
        Route::post('/{id}', [ProjectController::class, 'update']);
        Route::delete('/{id}', [ProjectController::class, 'delete']);
        Route::post('/projToTeam/{id}', [ProjectController::class, 'addRemoveProjectTeam']);
        Route::post('/roleToEmp/{id}', [ProjectController::class, 'roleToEmployee']);
    });

    //Role Route
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'getAll']);
        Route::post('/', [RoleController::class, 'add']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });
});

//Admins Routes
Route::group(['prefix' => 'admins'], function () {
    Route::get('/', [AdminController::class, 'getAllAdmins'])->middleware('cors');
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'authenticate']);
    Route::get('/{id}', [AdminController::class, 'getAdmin']);
    Route::post('/', [AdminController::class, 'addAdmin']);
    Route::delete('/{id}', [AdminController::class, 'deleteAdmin']);
    Route::post('/{id}', [AdminController::class, 'updateAdmin']);
    Route::post('/image/{id}', [AdminController::class, 'updateImageAdmin']);
});


//Login Route
Route::post('/login', [AdminController::class, 'authenticate']);
//Logout Route
Route::post('/logout', [AdminController::class, 'logOut']);
