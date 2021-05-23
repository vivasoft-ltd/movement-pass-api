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
    return 'Login first';
});
$router->post('/login', 'Admin\AuthController@login');

$router->group(['middleware' => ['auth:admin', 'jwtauth']], function () use ($router) {
    $router->get('/me', 'Admin\AuthController@me');
    $router->get('/logout', 'Admin\AuthController@logout');

    $router->get('/applications', 'Admin\ApplicationManageController@index');
    $router->get('/applications/{id}/approve', 'Admin\ApplicationManageController@approve');
    $router->get('/applications/{id}/reject', 'Admin\ApplicationManageController@reject');
});
