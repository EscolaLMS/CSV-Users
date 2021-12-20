<?php

// admin endpoints
use EscolaLms\CsvUsers\Http\Controllers\CsvUserAPIController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/admin/csv'], function () {
    Route::get('users', [CsvUserAPIController::class, 'export']);
    Route::post('users', [CsvUserAPIController::class, 'import']);
});
