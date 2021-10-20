<?php

Route::prefix('campaign')->name('campaign.')->group(function()
{

    Route::get('/list', [App\Http\Controllers\Agent\CampaignController::class, 'index'])->name('list');
    Route::get('/view-details/{id}', [App\Http\Controllers\Agent\CampaignController::class, 'show'])->name('show');
    Route::any('/get-campaigns', [App\Http\Controllers\Agent\CampaignController::class, 'getCampaigns'])->name('get_campaigns');


    Route::any('/start-campaign/{id}', [App\Http\Controllers\Agent\CampaignController::class, 'startCampaign'])->name('start_campaign');

});
