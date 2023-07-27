<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'desa'], function () use ($router) {
    $router->get('', ['uses' => 'DesaController@all']);
    $router->get('{id}', ['uses' => 'DesaController@show']);
    $router->post('create', ['uses' => 'DesaController@create']);
    $router->put('update', ['uses' => 'DesaController@update']);
    $router->delete('delete/{id}', ['uses' => 'DesaController@delete']);
});
