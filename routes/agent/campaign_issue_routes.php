<?php

Route::prefix('campaign-issue')->name('campaign_issue.')->group(function()
{

    Route::get('/list', [App\Http\Controllers\Agent\CampaignIssueController::class, 'index'])->name('list');
    Route::get('/view-details/{id}', [App\Http\Controllers\Agent\CampaignIssueController::class, 'show'])->name('show');
    Route::any('/get-issues', [App\Http\Controllers\Agent\CampaignIssueController::class, 'getIssues'])->name('get_issues');


    Route::any('/store', [App\Http\Controllers\Agent\CampaignIssueController::class, 'store'])->name('store');

});
