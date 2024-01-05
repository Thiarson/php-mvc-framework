<?php

  namespace Thiarson\Framework\core;

  use Thiarson\Framework\core\Routing\Router;

  class Application {
    /**
     * Instance of a router.
     * 
     * @var Router
     */
    public Router $router;

    public function __construct() {
      $this->router = new Router();
    }

    /**
     *  Initialize the application and resolve the current path.
     */
    public function run() {
      $this->router->resolve();
    }
  }
