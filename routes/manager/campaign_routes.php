<?php

Route::prefix('campaign')->name('campaign.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\Manager\CampaignController::class, 'index'])->name('list');
    Route::get('/view-details/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'show'])->name('show');

    Route::any('/get-campaigns', [App\Http\Controllers\Manager\CampaignController::class, 'getCampaigns'])->name('get_campaigns');

    Route::get('/create', [App\Http\Controllers\Manager\CampaignController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Manager\CampaignController::class, 'store'])->name('store');

    Route::any('/edit/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'edit'])->name('edit');
    Route::any('/update/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'update'])->name('update');

    Route::any('/destroy/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'destroy'])->name('destroy');

    Route::any('/validate-v-mail-campaign-id', [App\Http\Controllers\Manager\CampaignController::class, 'validateVMailCampaignId'])->name('validate.v_mail_campaign_id');

});
