<?php

  namespace Controllers;

  use Middlewares\AuthMiddleware;
  use Thiarson\Framework\Controllers\Controller;

  class HomeController extends Controller {
    public function __construct() {
      $this->registerMiddleware(new AuthMiddleware(['home.index']));
    }

    public function index() {
      $this->render('home.index', ['name' => 'Thiarson']);
    }
  }
