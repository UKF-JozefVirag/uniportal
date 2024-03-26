<?php

/** @var Router $router */

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

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {

});


$router->group(['prefix' => 'v1/oauth', 'middleware' => 'auth:api'], function () use ($router) {
    \Dusterio\LumenPassport\LumenPassport::routes($router);
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    Route::post('/logout', 'UserController@logout');
    Route::get('/getUserLoggedIn', 'UserController@getUserLoggedIn');
});

//tymto sme si vytvorili key pre aplikaciu, laravel pouziva tiez tento isty approach, vygeneruje 32 miestny nahodny string
//tento vygenerovany kluc sme vlozili do .env
// je to namiesto prikazu php artisan key:generate
//$router->get('/key', function() {
//    return \Illuminate\Support\Str::random(32);
//});

$router->post('/login', 'UserController@login');

$router->post('/register', 'UserController@register');

$router->get('/projects', 'ProjectsController@getProjects');
$router->get('/projects/synced', 'ProjectsController@getProjectsSynced');
Route::post('/import/projects', 'ProjectsController@importProjekty');
$router->get('/projectsNonSynchronized', 'ProjectsController@getProjectsNotSynchronized');

$router->get('/projects/vega', 'ProjectsController@getProjectsVega');
$router->get('/projects/kega', 'ProjectsController@getProjectsKega');
$router->get('/projects/apvv', 'ProjectsController@getProjectsApvv');

$router->get('/vega', 'ProjectsController@getVega');
Route::post('/import/vega', 'ProjectsController@importVega');

$router->get('/kega', 'ProjectsController@getKega');
Route::post('/import/kega', 'ProjectsController@importKega');

$router->get('/apvv', 'ProjectsController@getApvv');
Route::post('/import/apvv', 'ProjectsController@importApvv');

$router->get('/import/synchronize', 'ProjectsController@synchronizeProjects');
$router->post('/import/synchronize/manual', 'ProjectsController@manualSynchronizationProjects');

$router->get('/publications', 'PublicationsController@getPublikacie');
Route::post('/import/publications', 'PublicationsController@importPublikacie');

$router->get('/statistics/all', 'ProjectsController@getAllStatInfo');
$router->get('/statistics/faculty', 'ProjectsController@getShareByFaculty');
$router->get('/statistics/author', 'ProjectsController@getShareByAuthors');
$router->get('/statistics/TCategory', 'ProjectsController@getShareByCategoryT');
