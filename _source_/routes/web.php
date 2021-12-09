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

$router->post('/register', 'AuthController@register');
$router->post('/user/avatar-uploaded', 'AuthController@confirmImageUpload');
$router->post('/verify', 'AuthController@verify');
$router->post('/login', 'AuthController@login');
$router->get('/refresh', 'AuthController@refresh');

$router->group(['middleware' => 'auth'], function () use ($router)
{
    $router->get('/user', 'AuthController@me');
    $router->get('/pass', 'PassController@index');
    $router->post('/pass/create', 'PassController@store');

    $router->get('/logout', 'AuthController@logout');
});
