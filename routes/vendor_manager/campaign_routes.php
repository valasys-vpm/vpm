<?php

Route::prefix('campaign')->name('campaign.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\VendorManager\CampaignController::class, 'index'])->name('list');
    Route::get('/view-details/{id}', [App\Http\Controllers\VendorManager\CampaignController::class, 'show'])->name('show');
    Route::any('/get-campaigns', [App\Http\Controllers\VendorManager\CampaignController::class, 'getCampaigns'])->name('get_campaigns');

});
