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
    Route::group(['namespace' => 'App\Http\Controllers\API', 'as' => 'api'], function ($api) {
        Route::group(['prefix' => 'product', 'as' => '.product'], function ($api) {
            $api->get('/', ['as' => '.index', 'uses' => 'ProductController@index']);
            $api->post('/', ['as' => '.store', 'uses' => 'ProductController@store']);
            $api->get('/{id}', ['as' => '.show', 'uses' => 'ProductController@show']);
            $api->post('/{id}', ['as' => '.update', 'uses' => 'ProductController@update']);
            $api->delete('/{id}', ['as' => '.delete', 'uses' => 'ProductController@delete']);
        });
        Route::group(['prefix' => 'cart', 'as' => '.cart'], function ($api) {
            // $api->get('/', ['as' => '.index', 'uses' => 'ProductController@index']);
            $api->post('/', ['as' => '.store', 'uses' => 'CartController@store']);
            $api->get('/show', ['as' => '.show', 'uses' => 'CartController@show']);
            $api->post('/{id}', ['as' => '.update', 'uses' => 'CartController@update']);
            $api->delete('/{id}', ['as' => '.delete', 'uses' => 'CartController@delete']);
        });
        Route::group(['prefix' => 'transaction', 'as' => '.transaction'], function ($api) {
            $api->get('/', ['as' => '.index', 'uses' => 'TransactionController@index']);
            $api->post('/', ['as' => '.store', 'uses' => 'TransactionController@store']);
            $api->get('/{id}', ['as' => '.show', 'uses' => 'TransactionController@show']);
        });
    });
});
