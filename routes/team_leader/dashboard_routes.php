<?php

Route::prefix('dashboard')->name('dashboard.')->group(function()
{

    Route::any('/get-counts', [App\Http\Controllers\TeamLeader\DashboardController::class, 'getCounts'])->name('get_counts');

});
