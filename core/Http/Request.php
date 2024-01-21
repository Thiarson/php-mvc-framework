<?php

  namespace Thiarson\Framework\Http;

  class Request {
    /**
     * Get the path of the current request.
     *
     * @return  string  
     */
    public function getPath() {
      preg_match_all("#((?:/.*)+)\??[\w]*=?[\w]*(?:&?[\w]*=?[\w]*)*#", $_SERVER['REQUEST_URI'], $result, PREG_SET_ORDER);
      $path = $result[0][1];
      
      return $path;
    }

    /**
     * Get the method of the current request.
     *
     * @return  string  
     */
    public function getMethod() {
      return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Determine if the HTTP method is GET.
     *
     * @return  bool  
     */
    public function isGet() {
      return $this->getMethod() === 'GET';
    }

    /**
     * Determine if the HTTP method is POST.
     *
     * @return  bool  
     */
    public function isPost() {
      return $this->getMethod() === 'POST';
    }

    /**
     * Extract all the data sent in the request.
     *
     * @return   
     */
    public function getBody() {
      $body = [];
      
      if ($this->isGet()) {
        foreach ($_GET as $key => $value) {
          $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
      }

      if ($this->isPost()) {
        foreach ($_POST as $key => $value) {
          $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
      }

      return $body;
    }

    /**
     * Get the specified parameter in the url.
     * 
     * @var string $param
     * @return string|null
     */
    public function param(string $param, $default = null) {
      return $_GET[$param] ?? $default;
    }

    /**
     * Get all the parameters in the url.
     * 
     * @return array
     */
    public function all() {
      return $_GET;
    }
  }
