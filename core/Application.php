<?php

  namespace Thiarson\Framework;

  use Thiarson\Framework\Routing\Router;

  class Application {
    /**
     * Instance of a router.
     * 
     * @var Router
     */
    protected Router $router;

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
