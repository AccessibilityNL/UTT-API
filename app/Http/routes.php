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
    return 'utt-api';
});

$v1 = 'App\Http\Controllers\v1';
$v2 = 'App\Http\Controllers\v2';

$app->get('/{model}/context.jsonld', 'App\Http\Controllers\Controller@getContext');

$app->group([
    'prefix' => 'v1'
],
    function ($app) use ($v1) {
        /** @var \Laravel\Lumen\Application $app */

        $app->get('/', $v1 . '\WelcomeController@showIndex');

        $app->get('/assertions', $v1 . '\AssertionController@listAction');
        $app->get('/assertions/{id}', $v1 . '\AssertionController@getAction');

        $app->get('/webpages', $v1 . '\WebpageController@listAction');
        $app->get('/webpages/{id}', $v1 . '\WebpageController@getAction');
        $app->get('/webpages/{id}/assertions', $v1 . '\WebpageController@getAction');

        $app->get('/evaluations', $v1 . '\EvaluationController@listAction');
        $app->get('/evaluations/{id}', $v1 . '\EvaluationController@getAction');

        $app->post('/evaluations', $v1 . '\EvaluationController@createAction');

    });

$app->group([
    'prefix' => 'v2'
],
    function ($app) use ($v1, $v2) {
        /** @var \Laravel\Lumen\Application $app */

        $app->get('/', $v2 . '\WelcomeController@showIndex');

        $app->get('/evaluations', $v1 . '\EvaluationController@list');
        $app->get('/evaluations/{id}', $v1 . '\EvaluationController@get');

    });