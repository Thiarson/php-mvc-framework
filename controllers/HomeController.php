<?php

  namespace Controllers;

  use Middlewares\AuthMiddleware;
  use Middlewares\UserMiddleware;
  use Thiarson\Framework\Controllers\Controller;

  class HomeController extends Controller {
    public function __construct() {
      $middlewares = [
        new UserMiddleware(),
        new AuthMiddleware(['home.index']),
      ];
      $this->registerMiddleware($middlewares);
    }

    public function index() {
      $this->render('home.index', ['name' => 'Admin']);
    }
  }
