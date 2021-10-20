<?php

Route::prefix('campaign-assign')->name('campaign_assign.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\VendorManager\CampaignAssignController::class, 'index'])->name('list');
    Route::get('/view-details/{id}', [App\Http\Controllers\VendorManager\CampaignAssignController::class, 'show'])->name('show');
//
    Route::any('/get-assigned-campaigns', [App\Http\Controllers\VendorManager\CampaignAssignController::class, 'getAssignedCampaigns'])->name('get_assigned_campaigns');
//
//    Route::get('/create', [App\Http\Controllers\TeamLeader\CampaignAssignController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\VendorManager\CampaignAssignController::class, 'store'])->name('store');

    //Route::any('/view-assignment-details/{id}', [App\Http\Controllers\TeamLeader\CampaignAssignController::class, 'viewAssignmentDetails'])->name('view_assignment_details');

});
