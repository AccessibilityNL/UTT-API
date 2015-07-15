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

$app->get('/', function () {
    return view("index");
});

$v1 = 'App\Http\Controllers\v1';
$v2 = 'App\Http\Controllers\v2';

$app->get('/{version}/contexts/{model}.jsonld', 'App\Http\Controllers\Controller@getContext');

$app->group([
    'prefix' => 'v1',
    'middleware' => 'cors'
],
    function ($app) use ($v1) {
        /** @var \Laravel\Lumen\Application $app */

        $app->get('/', $v1 . '\WelcomeController@showIndex');

        $app->get('/assertors', $v1 . '\AssertorController@getOrCreateAction');
        $app->get('/assertors/{id}', $v1 . '\AssertorController@getAction');

        $app->get ('/assertions', $v1 . '\AssertionController@listAction');
        $app->post('/assertions', $v1 . '\AssertionController@createAction');
        $app->get ('/assertions/{id}', $v1 . '\AssertionController@getAction');

        $app->post('/webpages', $v1 . '\WebpageController@createAction');
        $app->get ('/webpages/{id}', $v1 . '\WebpageController@getAction');
        $app->get ('/webpages/{id}/assertions', $v1 . '\WebpageController@getAction');

        $app->get('/evaluations', $v1 . '\EvaluationController@listAction');
        $app->get('/evaluations/{id}', $v1 . '\EvaluationController@getAction');

        $app->post('/evaluations', $v1 . '\EvaluationController@createAction');
        $app->post('/evaluations/{id}/auditResult', $v1 . '\EvaluationController@addAction');

    });

$app->group([
    'prefix' => 'v2',
    'middleware' => 'cors'
],
    function ($app) use ($v1, $v2) {
        /** @var \Laravel\Lumen\Application $app */

        $app->get('/', $v2 . '\WelcomeController@showIndex');

    });