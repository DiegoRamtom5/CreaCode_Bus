<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\AutobusController;
use App\Http\Controllers\CorridaController;

// Autobuses
Route::post('/autobus/create', [AutobusController::class, 'create']);
Route::post('/autobus/update', [AutobusController::class, 'update']);
Route::post('/autobus/delete', [AutobusController::class, 'delete']);
Route::post('/autobus/view', [AutobusController::class, 'view']);

// Corridas
Route::post('/corrida/create', [CorridaController::class, 'create']);
Route::post('/corrida/update', [CorridaController::class, 'update']);
Route::post('/corrida/delete', [CorridaController::class, 'delete']);
Route::post('/corrida/view', [CorridaController::class, 'view']);


Route::post('/comprar-boleto', [BoletoController::class, 'comprarBoleto']);
Route::post('/cancelar-boleto', [BoletoController::class, 'cancelarBoleto']);
Route::post('/visualizar-boletos', [BoletoController::class, 'visualizarBoletos']);

Route::post('/registrar-incidente', [IncidenteController::class, 'registrarIncidente']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::middleware('validate.token')->group(function () {
    Route::post('/update-user', [UserController::class, 'update']);
    Route::post('/delete-user', [UserController::class, 'delete']);
});

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

