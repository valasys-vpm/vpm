<?php

Route::prefix('campaign')->name('campaign.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\Manager\CampaignController::class, 'index'])->name('list');
    Route::any('/get-campaigns', [App\Http\Controllers\Manager\CampaignController::class, 'getCampaigns'])->name('get_campaigns');

    Route::any('/store', [App\Http\Controllers\Manager\CampaignController::class, 'store'])->name('store');
    Route::any('/edit/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'edit'])->name('edit');
    Route::any('/update/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'update'])->name('update');

    Route::any('/destroy/{id}', [App\Http\Controllers\Manager\CampaignController::class, 'destroy'])->name('destroy');

});
