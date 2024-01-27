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
     * Contain all the routes name.
     * 
     * @var array
     */
    protected static array $name = [];

    /**
     * Contains all the path patterns.
     * 
     * @var array
     */
    protected static array $patterns = [];

    /**
     * Register a new GET route.
     *
     * @param  string  $path
     * @param  string|array|function  $action
     */
    public static function get(string $path, $action) {
      $patterns = new Pattern();

      self::$routes['GET'][$path] = $action;

      if (is_string($action)) {
        self::$name[$action] = [
          'method' => 'GET',
          'path' => $path,
        ];
      }

      if (preg_match_all("#.+({.+})#U", $path)) {
        $pattern = preg_replace_callback("#(.+)({.+})#U", function ($matches) {
          return $matches[1].'([\w]*)';
        }, $path);
  
        self::$patterns['GET'][$path] = $pattern;
      }
      
      $patterns->setMethod('GET');
      $patterns->setPath($path);

      return $patterns;
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

    /**
     * Get all the patterns.
     * 
     * @return array
     */
    public static function getPatterns() {
      return self::$patterns;
    }

    /**
     * Modify the patterns.
     * 
     * @param string $method
     * @param string $path
     * @param string $pattern
     */
    public static function setPatterns(string $method, string $path, string $pattern) {
      self::$patterns[$method][$path] = $pattern;
    }

    /**
     * Get the specified name.
     * 
     * @return array
     */
    public static function getName(string $name) {
      return self::$name[$name];
    }

    /**
     * Modify the name.
     * 
     * @param string $name
     * @param string $method
     * @param string $path
     */
    public static function setName(string $name, string $method, string $path) {
      self::$name[$name] = [
        'method' => $method,
        'path' => $path,
      ];
    }
  }
