<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get('user', 'AuthController@user');
});

Route::group([

    'middleware' => ['api', 'auth:api']

], function ($router) {
    Route::apiResources([
      'boards' => 'API\BoardController',
      'boardLists' => 'API\BoardListController',
      'cards' => 'API\CardController'
    ]);

    Route::patch('board/updateOrder', 'API\BoardController@updateOrder');
    Route::patch('board/updateListOrder', 'API\BoardController@updateListOrder');
});
