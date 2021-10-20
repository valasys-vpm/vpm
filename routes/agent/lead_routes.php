<?php

Route::prefix('lead')->name('lead.')->group(function()
{

    Route::get('/{id}/list', [App\Http\Controllers\Agent\LeadController::class, 'index'])->name('list');
    Route::get('/{id}/create', [App\Http\Controllers\Agent\LeadController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Agent\LeadController::class, 'store'])->name('store');

    Route::any('/get-leads', [App\Http\Controllers\Agent\LeadController::class, 'getLeads'])->name('get_leads');


    Route::any('/start-campaign/{id}', [App\Http\Controllers\Agent\LeadController::class, 'startCampaign'])->name('start_campaign');

});
