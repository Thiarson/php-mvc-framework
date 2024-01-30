<?php

  namespace Thiarson\Framework\Routing;

  class Pattern {
    /**
     * Contain the method of the current route.
     * 
     * @var string
     */
    protected string $method;

    /**
     * Contain the path of the current route.
     * 
     * @var string
     */
    protected string $path;

    /**
     * Define if the pattern is prefixed.
     * 
     * @var bool
     */
    protected bool $isPrefixed;

    /**
     * Used as buffer in this class.
     * 
     * @var mixed
     */
    protected $temp;

    public function __construct() {
      $this->method = '';
      $this->path = '';
      $this->isPrefixed = false;
      $this->temp = null;
    }

    /**
     * Used to specify the parameters in the path.
     * 
     * @param array $params
     * @return Patterns
     */
    public function where(array $params) {
      $this->temp = $params;

      if (preg_match_all("#.+({.+})#U", $this->path)) {
        $pattern = preg_replace_callback("#(.+)({(.+)})#U", function ($matches) {
          $regexClass = $this->temp[$matches[3]];
          return $matches[1]."($regexClass)";
        }, $this->path);

        Route::setPatterns($this->method, $this->path, $pattern);
      }

      return $this;
    }

    /**
     * Create a name for the specified route.
     * 
     * @param string $name
     * @return Pattern
     */
    public function name(string $name) {
      if (!$this->isPrefixed) {
        $name = Route::getCurrentName().$name;
        Route::setName($name, $this->method, $this->path);
      }
      else {
        Route::setCurrentName($name);
      }

      return $this;
    }

    /**
     * Regroupe some routes with the same prefix.
     * 
     * @param callable $callback
     * @return Pattern
     */
    public function group(callable $callback) {
      call_user_func($callback);
      Route::resetCurrentPrefix();
      Route::setCurrentName('');

      return $this;
    }

    /**
     * Modify the method.
     * 
     * @param string $method
     */
    public function setMethod(string $method) {
      $this->method = $method;
    }

    /**
     * Modify the path.
     * 
     * @param string $path
     */
    public function setPath(string $path) {
      $this->path = $path;
    }

    /**
     * Modify the state of isPrefixed.
     * 
     * @param bool $value
     */
    public function setIsPrefixed(bool $value) {
      $this->isPrefixed = $value;
    }
  }
