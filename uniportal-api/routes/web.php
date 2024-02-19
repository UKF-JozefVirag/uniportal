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

$router->get('/registerx', 'UserController@registerx');
$router->post('/register', 'UserController@register');

$router->get('/import/projects', 'ProjektyController@importProjekty');
$router->get('/projects', 'ProjektyController@getProjects');

$router->get('/vega', 'ProjektyController@getVega');
$router->get('/import/vega', 'ProjektyController@importVega');

$router->get('/kega', 'ProjektyController@getKega');
$router->get('/import/kega', 'ProjektyController@importKega');

$router->get('/apvv', 'ProjektyController@getApvv');
$router->get('/import/apvv', 'ProjektyController@importApvv');

$router->get('/import/synchronize', 'ProjektyController@synchronizeProjects');


