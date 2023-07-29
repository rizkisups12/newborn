<?php

use Illuminate\Support\Facades\Route;

$router->group(['prefix' => 'newborn'], function () use ($router) {
    $router->get('/data', 'DataController@getData');
    $router->get('/data/{id}', 'DataController@show');
    $router->get('/datefilter/{date}', 'DataController@dataByFilterDate');
    $router->get('/yearfilter/{year}', 'DataController@dataByFilterYear');
    $router->post('/store', 'DataController@store');
    $router->put('/update/{id}', 'DataController@update');
    $router->delete('/delete/{id}/{id_seq}', 'DataController@destroy');
});

