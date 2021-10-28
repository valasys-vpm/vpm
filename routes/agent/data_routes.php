<?php

Route::prefix('data')->name('data.')->group(function()
{
    Route::get('/list/{id}', [App\Http\Controllers\Agent\DataController::class, 'index'])->name('list');

    Route::any('/get-agent-data', [App\Http\Controllers\Agent\DataController::class, 'getAgentData'])->name('get_agent_data');

});
