<?php
use App\Http\Controllers\LocationController;
/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'validation'], function () use ($router) {
    // Tüm route'lar bu grup altında ve validation middleware'inden geçiyor
    $router->post('/location/create', 'LocationController@create');
    $router->post('/location/list', 'LocationController@list');
    $router->put('/location/update/{id}', 'LocationController@update');
    $router->delete('/location/delete/{id}', 'LocationController@delete');
    $router->post('/location/{id}', 'LocationController@info');
    $router->get('/location', 'LocationController@view');
});
