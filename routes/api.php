<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return ['Chegamos aqui' => 'SIM'];
});

Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
    Route::apiResource('carro', 'App\Http\Controllers\CarroController');
    Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
    Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
    Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
});

// Route::middleware('jwt.auth')->group(function() {
//     Route::post('login', 'App\Http\Controllers\AuthController@login');
//     Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
//     Route::post('me', 'App\Http\Controllers\AuthController@me');
//     Route::post('logout', 'App\Http\Controllers\AuthController@logout');
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

});