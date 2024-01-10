<?php

  namespace Controllers;

  use Middlewares\AuthMiddleware;
  use Thiarson\Framework\Controllers\Controller;

  class HomeController extends Controller {
    public function __construct() {
      $middlewares = [
        new AuthMiddleware(['home.index'], false),
      ];
      $this->registerMiddleware($middlewares);
    }

    public function index() {
      $this->render('home.index', ['name' => 'Admin']);
    }
  }
