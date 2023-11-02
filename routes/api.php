<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\EmployeesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    //companies
    Route::get('companies', [CompaniesController::class, 'indexCompanies']);
    Route::post('companies', [CompaniesController::class, 'storeCompanies']);
    Route::get('companies/{id}', [CompaniesController::class, 'showCompanies']);
    Route::post('companies/{id}', [CompaniesController::class, 'updateCompanies']);
    Route::delete('companies/{id}', [CompaniesController::class, 'destroyCompanies']);

    //employees
    Route::get('employees', [EmployeesController::class, 'indexEmployees']);
    Route::post('employees', [EmployeesController::class, 'storeEmployees']);
    Route::get('employees/{id}', [EmployeesController::class, 'showEmployees']);
    Route::post('employees/{id}', [EmployeesController::class, 'updateEmployees']);
    Route::delete('employees/{id}', [EmployeesController::class, 'destroyEmployees']);
});
