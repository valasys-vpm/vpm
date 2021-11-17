<?php

Route::prefix('lead')->name('lead.')->group(function()
{

    Route::get('/{id}/list', [App\Http\Controllers\TeamLeader\LeadController::class, 'index'])->name('list');

    Route::any('/get-agent-leads', [App\Http\Controllers\TeamLeader\LeadController::class, 'getAgentLeads'])->name('get_agent_leads');

    Route::any('/start-campaign/{id}', [App\Http\Controllers\TeamLeader\LeadController::class, 'startCampaign'])->name('start_campaign');

});
