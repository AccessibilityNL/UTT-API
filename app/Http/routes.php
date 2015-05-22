<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', 'App\Http\Controllers\Controller@showHome');

$app->group([
    'namespace' => 'App\Http\Controllers\v1',
    'prefix' => 'v1'
], function ($app) {

    /** @var \Laravel\Lumen\Application $app */
    $app->get('/', 'WelcomeController@showIndex');

});
