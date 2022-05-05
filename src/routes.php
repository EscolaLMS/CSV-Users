<?php

// admin endpoints
use EscolaLms\CsvUsers\Http\Controllers\CsvUserAPIController;
use EscolaLms\Core\Http\Facades\Route;

Route::group(['middleware' => Route::apply(['auth:api']), 'prefix' => 'api/admin/csv'], function () {
    Route::get('users', [CsvUserAPIController::class, 'export']);
    Route::post('users', [CsvUserAPIController::class, 'import']);
});
