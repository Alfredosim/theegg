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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});


Route::group(['middleware' => 'jwt.auth'], function ($router) {
	// Users routes
    Route::get('usuarios', 'UserController@index');
    Route::post('usuarios/crear', 'UserController@create');
    Route::get('usuarios/stats', 'UserController@stats');

    // Categorias routes
    Route::post('categorias', 'CategoriaController@index');
    Route::get('categoriasv2', 'CategoriaController@list');
    Route::post('categoria/crear', 'CategoriaController@store');
    Route::get('categoria/{id}', 'CategoriaController@show');
    Route::put('categoria/{id}', 'CategoriaController@update');

    // Transacciones routes
    Route::post('transacciones', 'TransaccionController@index');
    Route::post('transaccion/crear', 'TransaccionController@store');
    Route::get('transaccion/{id}', 'TransaccionController@show');
    Route::put('transaccion/{id}', 'TransaccionController@update');
    Route::delete('transaccion/{id}', 'TransaccionController@destroy');

    //Dashboard routes
    
    
});

Route::get('dashboard', 'DashboardController@home');
Route::get('/lastweek', 'DashboardController@lastWeek');
Route::get('/lastmonth', 'DashboardController@lastMonth');
Route::get('/lastyear', 'DashboardController@lastYear');