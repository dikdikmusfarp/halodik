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

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/status', [\App\Http\Controllers\Api\AuthController::class, 'status']);
    Route::group(['namespace' => 'App\Http\Controllers\API', 'as' => 'api'], function ($api) { # --- operator
        Route::group(['prefix' => 'user', 'as' => '.user'], function ($api) {
            $api->get('/list', ['as' => '.store', 'uses' => 'UserController@list']);
            // $api->post('/', ['as' => '.store', 'uses' => 'TypeController@store']);
            // $api->get('/list', ['as' => '.list', 'uses' => 'TypeController@list']);
            // $api->get('/{id}', ['as' => '.show', 'uses' => 'TypeController@show']);
            // $api->post('/{id}', ['as' => '.update', 'uses' => 'TypeController@update']);
            // $api->delete('/{id}', ['as' => '.delete', 'uses' => 'TypeController@delete']);
        });

        Route::group(['prefix' => 'chat', 'as' => '.chat'], function ($api) {
            $api->post('/', ['as' => '.store', 'uses' => 'ChatController@store']);
            $api->post('/{id}', ['as' => '.read', 'uses' => 'ChatController@read']);
            $api->get('/room/{id}', ['as' => '.chat-room', 'uses' => 'ChatController@chatRoom']);
        });
    });
});

