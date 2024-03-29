<?php

  namespace Thiarson\Framework\Controllers;

  use Thiarson\Framework\Middlewares\Middleware;
  use Thiarson\Framework\Views\View;

  class Controller {
    /**
     * Contains all the middlewares
     * 
     * @var Middleware[]
     */
    protected array $middlewares = [];

    /**
     * Implement the render method based on the view render, so every controller that extends this base controller will have this method.
     * 
     * @param $view
     * @param array $params
     */
    public function render($view, array $params = []) {
      $views = new View();
      $views->render($view, $params);
    }

    /**
     * Add new middleware.
     * 
     * @param Middleware[] $middleware
     */
    public function registerMiddleware(array $middlewares = []) {
      foreach ($middlewares as $middleware) {
        $this->middlewares[] = $middleware;
      }
    }

    /**
     * Get all the stored middlewares.
     * 
     * @return 
     */
    public function getMiddlewares() {
      return $this->middlewares;
    }
  }
