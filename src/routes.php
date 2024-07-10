<?php

// admin endpoints
use EscolaLms\CsvUsers\Http\Controllers\CsvGroupAPIController;
use EscolaLms\CsvUsers\Http\Controllers\CsvUserAPIController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/admin/csv'], function () {
    Route::prefix('users')->group(function () {
        Route::get('', [CsvUserAPIController::class, 'export']);
        Route::post('' , [CsvUserAPIController::class, 'import']);
    });
    Route::prefix('groups')->group(function () {
        Route::get('{group}', [CsvGroupAPIController::class, 'export']);
        Route::post('' , [CsvGroupAPIController::class, 'import']);
    });
});
