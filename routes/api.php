<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/order/total', [OrderController::class, 'total']);

    // Route pour tester rapidement
    Route::get('/order/test', function () {
        return response()->json([
            'message' => 'API de test de commande opérationnelle',
            'version' => '1.0',
            'laravel_version' => app()->version()
        ]);
    });
});
