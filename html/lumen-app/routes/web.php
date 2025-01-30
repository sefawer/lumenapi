<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'validation'], function () use ($router) {
    // Tüm route'lar bu grup altında ve validation middleware'inden geçiyor
    $router->post('/location/create', 'LocationController@create');
    $router->post('/location/list', 'LocationController@list');
    $router->post('/location/update/{id}', 'LocationController@update');
    $router->post('/location/{id}', 'LocationController@info');
    $router->get('/location', 'LocationController@view');
});
