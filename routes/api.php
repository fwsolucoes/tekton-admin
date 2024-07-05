<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpoCatolica\ExpoController;
use App\Http\Controllers\Tiradentes\TiradentesController;
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

Route::post('/v1/expressions', [ExpressionsController::class, 'catalogo'])->withoutMiddleware("throttle:api");
Route::post('/v1/expressions-formulario', [ExpressionsController::class, 'formulario'])->withoutMiddleware("throttle:api");
Route::post('/v1/webhook-shopify', [CyroController::class, 'shopify_webhook'])->withoutMiddleware("throttle:api");
Route::post('/v1/cartpanda-webhook', [CyroController::class, 'cartpanda_webhook'])->withoutMiddleware("throttle:api");
Route::post('/products', [CyroController::class, 'products'])->withoutMiddleware("throttle:api");


Route::get('/v1/teste', [TiradentesController::class, 'teste'])->withoutMiddleware("throttle:api");

Route::post('/v1/expocatolica', [ExpoController::class, 'formulario'])->withoutMiddleware("throttle:api");
