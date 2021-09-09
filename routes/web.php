<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['guest'])->group(function (){
    Route::view('/login', 'login')->name('login');
});

Route::any('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');


Route::prefix('admin')->middleware(['web', 'check.admin'])->name('admin.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    //Role Management Routes
    include('admin/role_routes.php');
    //Department Management Routes
    include('admin/department_routes.php');
    //Designation Management Routes
    include('admin/designation_routes.php');

    //Campaign Settings Routes
    include('admin/campaign_filter_routes.php');
    include('admin/campaign_type_routes.php');

    //Geo Management Routes
    include('admin/geo_routes.php');

    //Site Setting Routes
    include('admin/site_setting_routes.php');
    //Holiday Management Routes
    include('admin/holiday_routes.php');
});

Route::prefix('manager')->middleware(['web', 'check.manager'])->name('manager.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'index'])->name('dashboard');


});

Route::prefix('team-leader')->middleware(['web', 'check.team_leader'])->name('team_leader.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\TeamLeader\DashboardController::class, 'index'])->name('dashboard');

});

Route::middleware(['web', 'check.agent'])->name('agent.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

});




