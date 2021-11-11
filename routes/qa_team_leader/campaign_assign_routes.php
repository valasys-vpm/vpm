<?php

Route::prefix('campaign-assign')->name('campaign_assign.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\QATeamLeader\CampaignAssignController::class, 'index'])->name('list');

    Route::get('/view-details/{id}', [App\Http\Controllers\QATeamLeader\CampaignAssignController::class, 'show'])->name('show');

    Route::any('/get-assigned-campaigns', [App\Http\Controllers\QATeamLeader\CampaignAssignController::class, 'getAssignedCampaigns'])->name('get_assigned_campaigns');

    Route::get('/create', [App\Http\Controllers\QATeamLeader\CampaignAssignController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\QATeamLeader\CampaignAssignController::class, 'store'])->name('store');

    Route::any('/submit-campaign/{id}', [App\Http\Controllers\QATeamLeader\CampaignAssignController::class, 'submitCampaign'])->name('submit_campaign');

});
