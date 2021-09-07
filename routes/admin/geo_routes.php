<?php

Route::prefix('geo')->name('geo.')->group(function()
{
    Route::prefix('region')->name('region.')->group(function()
    {
        Route::get('/list', [App\Http\Controllers\Admin\GeoController::class, 'regionIndex'])->name('list');
        Route::any('/get-regions', [App\Http\Controllers\Admin\GeoController::class, 'getRegions'])->name('get_regions');
        Route::any('/store', [App\Http\Controllers\Admin\GeoController::class, 'regionStore'])->name('store');
        Route::any('/edit/{id}', [App\Http\Controllers\Admin\GeoController::class, 'regionEdit'])->name('edit');
        Route::any('/update/{id}', [App\Http\Controllers\Admin\GeoController::class, 'regionUpdate'])->name('update');
        Route::any('/destroy/{id}', [App\Http\Controllers\Admin\GeoController::class, 'regionDestroy'])->name('destroy');
    });
    
});
