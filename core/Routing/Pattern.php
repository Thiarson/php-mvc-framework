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
     * Used as buffer in this class.
     * 
     * @var mixed
     */
    protected $temp;

    public function __construct() {
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
  }
