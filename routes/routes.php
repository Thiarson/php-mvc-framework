<?php

  use Thiarson\Framework\Routing\Route;
  use Thiarson\Framework\Views\View;

  // We register here all the routes for the application.

  Route::get('/', function () {
    View::view('landing');
  });

  Route::get('/login', 'auth.login');
  Route::post('/login', 'auth.login');
  Route::get('/logout', 'auth.logout');

  Route::get('/home', 'home.index');
