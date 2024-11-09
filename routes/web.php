<?php

use App\Router;

// Pages routes 

$router->get(ENTRY_POINT.'', 'PageController@login');
$router->get(ENTRY_POINT.'about', 'PageController@about');
$router->get(ENTRY_POINT.'dashboard', 'PageController@dashboard');

// Auth routes

$router->get(ENTRY_POINT.'login', 'AuthController@login');
$router->get(ENTRY_POINT.'register', 'AuthController@register');
$router->post(ENTRY_POINT.'logout', 'AuthController@logout');
$router->post(ENTRY_POINT.'api/login', 'AuthController@authenticate');


// clients routes

$router->get(ENTRY_POINT.'clients', 'ClientController@index');
$router->get(ENTRY_POINT.'clients/create', 'ClientController@create');
$router->post(ENTRY_POINT.'clients/store', 'ClientController@store');
$router->get(ENTRY_POINT.'clients/{id}', 'ClientController@show');
$router->get(ENTRY_POINT.'clients/{id}/edit', 'ClientController@edit'); 
$router->post(ENTRY_POINT.'clients/{id}', 'ClientController@update');
$router->post(ENTRY_POINT.'clients/{id}/delete', 'ClientController@destroy');


// user routes

$router->get(ENTRY_POINT.'users', 'UserController@index');
$router->get(ENTRY_POINT.'users/create', 'UserController@create');
$router->post(ENTRY_POINT.'users/store', 'UserController@store');
$router->get(ENTRY_POINT.'users/{id}', 'UserController@show');
$router->get(ENTRY_POINT.'users/{id}/edit', 'UserController@edit'); 
$router->post(ENTRY_POINT.'users/{id}', 'UserController@update');
$router->post(ENTRY_POINT.'users/{id}/delete', 'UserController@destroy'); 

