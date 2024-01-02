<?php

use App\Http\Controllers\API\ReminderController;
use App\Http\Controllers\API\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('session', [SessionController::class, 'login']);

Route::prefix('reminders')->group(function ($routes) {
    $routes->get('', [ReminderController::class, 'index']);
    $routes->get('{id}', [ReminderController::class, 'show']);
    $routes->post('', [ReminderController::class, 'store']);
    $routes->put('{id}', [ReminderController::class, 'update']);
    $routes->delete('{id}', [ReminderController::class, 'destroy']);
});
