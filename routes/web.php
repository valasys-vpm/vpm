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

    //User Management Routes
    include('admin/user_routes.php');

    //Role Management Routes
    include('admin/role_routes.php');
    //Department Management Routes
    include('admin/department_routes.php');
    //Designation Management Routes
    include('admin/designation_routes.php');

    //Campaign Settings Routes
    include('admin/campaign_filter_routes.php');
    include('admin/campaign_type_routes.php');
    include('admin/campaign_status_routes.php');

    //Geo Management Routes
    include('admin/geo_routes.php');

    //Site Setting Routes
    include('admin/site_setting_routes.php');

    //Holiday Management Routes
    include('admin/holiday_routes.php');
});

Route::prefix('manager')->middleware(['web', 'check.manager'])->name('manager.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'index'])->name('dashboard');

    //Campaign Management Routes
    include('manager/campaign_routes.php');

    //Campaign Assign Routes
    include('manager/campaign_assign_routes.php');

    //Holiday Routes
    include('manager/holiday_routes.php');

    //Data Routes
    include('manager/data_routes.php');

});

Route::prefix('team-leader')->middleware(['web', 'check.team_leader'])->name('team_leader.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\TeamLeader\DashboardController::class, 'index'])->name('dashboard');

    //Campaign Management Routes
    include('team_leader/campaign_routes.php');

    //Campaign Assign Routes
    include('team_leader/campaign_assign_routes.php');

    //Team Management Routes
    include('team_leader/team_routes.php');

    //Campaign Management Routes
    include('team_leader/lead_routes.php');

    //Data Routes
    include('team_leader/data_routes.php');

});

Route::prefix('agent')->middleware(['web', 'check.agent'])->name('agent.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

    //Campaign Management Routes
    include('agent/campaign_routes.php');

    //Campaign Management Routes
    include('agent/lead_routes.php');

    //Data Routes
    include('agent/data_routes.php');

});

Route::prefix('qa-team-leader')->middleware(['web', 'check.qa_team_leader'])->name('qa_team_leader.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\QATeamLeader\DashboardController::class, 'index'])->name('dashboard');

    //Campaign Management Routes
    include('qa_team_leader/campaign_routes.php');

    //Campaign Assign Routes
    include('qa_team_leader/campaign_assign_routes.php');

    //Team Management Routes
    include('qa_team_leader/team_routes.php');

});

Route::prefix('quality-analyst')->middleware(['web', 'check.quality_analyst'])->name('quality_analyst.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\QualityAnalyst\DashboardController::class, 'index'])->name('dashboard');

    //Campaign Management Routes
    include('quality_analyst/campaign_routes.php');

});

Route::prefix('email-marketing-executive')->middleware(['web', 'check.email_marketing_executive'])->name('email_marketing_executive.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\EmailMarketingExecutive\DashboardController::class, 'index'])->name('dashboard');

    //Campaign Management Routes
    include('email_marketing_executive/campaign_routes.php');

});

Route::prefix('vendor-manager')->middleware(['web', 'check.vendor_manager'])->name('vendor_manager.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\VendorManager\DashboardController::class, 'index'])->name('dashboard');

    //Vendor Routes
    include('vendor_manager/vendor_routes.php');

    //Campaign Management Routes
    include('vendor_manager/campaign_routes.php');

    //Campaign Assign Routes
    include('vendor_manager/campaign_assign_routes.php');

});

//Notification Routes

Route::any('/notification/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notification.mark_all_as_read');
Route::any('/notification/view-details/{id}', [App\Http\Controllers\NotificationController::class, 'update'])->name('notification.view_details');




