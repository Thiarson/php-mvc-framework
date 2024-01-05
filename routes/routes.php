<?php

  use Thiarson\Framework\core\Routing\Route;

  // We register here all the routes for the application.

  Route::get('/', 'home.landing');
  Route::get('/home', function () {
    echo 'Home';
  });
