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
$router->get('/ripUsers', 'UserController@ripUsers');

$router->get('/projects', 'ProjektyController@getProjects');
$router->get('/projects/synced', 'ProjektyController@getProjectsSynced');
Route::post('/import/projects', 'ProjektyController@importProjekty');
$router->get('/projectsNonSynchronized', 'ProjektyController@getProjectsNotSynchronized');


$router->get('/projects/vega', 'ProjektyController@getProjectsVega');
$router->get('/projects/kega', 'ProjektyController@getProjectsKega');
$router->get('/projects/apvv', 'ProjektyController@getProjectsApvv');
$router->get('/projects/sync', 'ProjektyController@synchronizeProjects');

$router->get('/vega', 'ProjektyController@getVega');
$router->get('/import/vega', 'ProjektyController@importVega');

$router->get('/kega', 'ProjektyController@getKega');
$router->get('/import/kega', 'ProjektyController@importKega');

$router->get('/apvv', 'ProjektyController@getApvv');
$router->get('/import/apvv', 'ProjektyController@importApvv');

$router->get('/import/synchronize', 'ProjektyController@synchronizeProjects');
$router->post('/import/synchronize/manual', 'ProjektyController@manualSynchronizationProjects');

$router->get('/publications', 'PublikacieController@getPublikacie');
$router->get('/import/publications', 'PublikacieController@importPublikacie');

$router->get('/statistics/all', 'ProjektyController@getAllStatInfo');
$router->get('/statistics/faculty', 'ProjektyController@getShareByFaculty');
$router->get('/statistics/author', 'ProjektyController@getShareByAuthors');
