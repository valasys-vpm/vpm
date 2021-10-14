<?php

Route::prefix('campaign-assign')->name('campaign_assign.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\Manager\CampaignAssignController::class, 'index'])->name('list');
    Route::any('/get-campaigns', [App\Http\Controllers\Manager\CampaignAssignController::class, 'getCampaigns'])->name('get_campaigns');

    Route::get('/create', [App\Http\Controllers\Manager\CampaignAssignController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Manager\CampaignAssignController::class, 'store'])->name('store');

    Route::any('/edit-pacing-details/{id}', [App\Http\Controllers\Manager\CampaignAssignController::class, 'editPacingDetails'])->name('edit_pacing_details');
    Route::any('/update/{id}', [App\Http\Controllers\Manager\CampaignAssignController::class, 'update'])->name('update');
    Route::any('/destroy/{id}', [App\Http\Controllers\Manager\CampaignAssignController::class, 'destroy'])->name('destroy');

});
