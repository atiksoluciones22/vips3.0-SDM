<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController, SettingController};
use App\Http\Controllers\HumanCapital\{EmployeeController, ActionRequestController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::redirect('/', '/login');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    Route::prefix('human-capital')->name('human-capital.')->group(function () {
        Route::get('/action-requests', [ActionRequestController::class, 'index'])->name('action-requests');
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    });
});
