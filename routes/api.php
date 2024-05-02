<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SSEController;
use App\Http\Controllers\usuarioscontroller;
use App\Http\Controllers\juegosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

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
Route::get('/sse', [SSEController::class ,'sendSSE']);

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('mandarcorreo', [AuthController::class, 'mandarcorreo']);
    Route::post('verify-code', 'AuthController@verifyCode')->name('verifyCode');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get ('/activate/{token}', [AuthController::class ,'activate'])->name('activate');
});

Route::middleware(['auth:api', RoleMiddleware::class . ':3'])->group(function () {
    Route::post('/juego', [juegosController::class, 'store'])->name('createJuego');
    Route::get('/juego', [juegosController::class, 'indexEnEspera'])->name('indexEnEspera');
    Route::post('/juego/casilla', [juegosController::class, 'receiveCasilla']);
    Route::get('/juego/{id}', [juegosController::class, 'show'])->where('id', '[0-9]+')->name('showJuego');
    Route::get('/juego/finalizados', [juegosController::class, 'indexFinalizados'])->name('indexFinalizados');
    Route::put('/juego/{id}', [juegosController::class, 'joinGame'])->where('id', '[0-9]+')->name('joinGame');
    Route::put('/juego/finalizar/{id}', [juegosController::class, 'finishGame'])->where('id', '[0-9]+')->name('finishGame');
    Route::put('/juego/score/{id}', [juegosController::class, 'updateScore'])->where('id', '[0-9]+')->name('updateScore');
    Route::get('/juego/change', [juegosController::class, 'changescreen'])->name('changescreen');
    Route::get('/juego/usuarios/{id}', [juegosController::class, 'getUserId'])->where('id', '[0-9]+')->name('getUserId');
    Route::put('/juego/turno/{id}', [juegosController::class, 'nextTurn'])->where('id', '[0-9]+')->name('updateTurno');
    Route::put('/juego/cancelar/{id}', [juegosController::class, 'cancelGame'])->where('id', '[0-9]+');
});

Route::middleware(['auth:api',RoleMiddleware::class . ':3'])->group(function () {
    Route::get('/usuarios/current', [AuthController::class, 'getCurrentUserId'])->name('usuarioId');
    Route::get('/usuarios', [usuarioscontroller::class, 'index'])->name('allusuarios');
    Route::post('/usuarios', [usuarioscontroller::class, 'store'])->name('createusuarios');
    Route::get('/usuarios/{combo}', [usuarioscontroller::class, 'show'])->where('combo', '[0-9]+')->name('showusuarios');
    Route::put('/usuarios/{combo}', [usuarioscontroller::class, 'update'])->where('combo', '[0-9]+')->name('updateusuarios');
    Route::delete('/usuarios/{combo}', [usuarioscontroller::class, 'destroy'])->where('combo', '[0-9]+')->name('deleteusuarios');
    Route::get('/roles',[usuarioscontroller::class,'showroles']);
    Route::get('/logs',[usuarioscontroller::class,'logs']);
    Route::post('activateUser/{id}', [usuarioscontroller::class, 'activateUser'])->where('id', '[0-9]+')->name('activateUser');
    Route::post('deactivateUser/{id}', [usuarioscontroller::class, 'deactivateUser'])->where('id', '[0-9]+')->name('deactivateUser');
});