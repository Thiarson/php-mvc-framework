<?php

  namespace Thiarson\Framework\Controllers;

  use Middlewares\UserMiddleware;

  class FuncController extends Controller {
    public function __construct() {
      $middlewares = [
        new UserMiddleware(),
      ];
      $this->registerMiddleware($middlewares);
    }
  }
