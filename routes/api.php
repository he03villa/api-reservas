<?php

use App\Http\Controllers\ReservaCotroller;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/reserva', [ReservaCotroller::class, 'index']);
Route::post('/reserva', [ReservaCotroller::class, 'store']);
Route::put('/reserva/{reserva}', [ReservaCotroller::class, 'update']);
Route::put('/reserva/estado_reserva/{reserva}', [ReservaCotroller::class, 'editEstadoReserva']);
