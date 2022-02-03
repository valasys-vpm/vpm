<?php

Route::prefix('lead')->name('lead.')->group(function()
{

    Route::get('/{id}/list', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'index'])->name('list');
    Route::get('/{id}/create', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'store'])->name('store');

    Route::get('edit/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'update'])->name('update');

    Route::post('destroy/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'destroy'])->name('destroy');

    Route::any('/get-leads', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'getLeads'])->name('get_leads');


    Route::any('/start-campaign/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'startCampaign'])->name('start_campaign');

    //Validate Suppression
    Route::any('/check-suppression-email/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'checkSuppressionEmail'])->name('check_suppression_email');
    Route::any('/check-suppression-domain/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'checkSuppressionDomain'])->name('check_suppression_domain');
    Route::any('/check-suppression-account-name/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'checkSuppressionAccountName'])->name('check_suppression_account_name');

    //Validate Target List
    Route::any('/check-target-domain/{id}', [App\Http\Controllers\VendorManager\RA\LeadController::class, 'checkTargetDomain'])->name('check_target_domain');

});
