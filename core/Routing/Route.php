<?php

  namespace Thiarson\Framework\core\Routing;

  class Route {
    /**
     * Contain all the routes.
     *
     * @var array
     */
    public static array $routes = [];

    /**
     * Register a new GET route.
     *
     * @param  string  $path
     * @param  string|array|function  $action
     */
    public static function get(string $path, $action) {
      self::$routes['GET'][$path] = $action;
    }

    /**
     * Register a new POST route.
     *
     * @param  string  $path
     * @param  string|array|function $action
     */
    public static function post(string $path, $action) {
      self::$routes['POST'][$path] = $action;
    }
  }
