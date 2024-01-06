<?php

  namespace Thiarson\Framework\Routing;

  class Route {
    /**
     * Contain all the routes.
     *
     * @var array
     */
    protected static array $routes = [];

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

    /**
     * Get the action that match with the path.
     *
     * @param  string  $method
     * @param  string  $path
     * @return  string|array|function  $action
     */
    public static function getAction(string $method, string $path) {
      return self::$routes[$method][$path] ?? null;
    }
  }
