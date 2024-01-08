<?php

  namespace Thiarson\Framework\Http;

  class Request {
    /**
     * Get the path of the current request.
     *
     * @return  string  
     */
    public function getPath() {
      return $_SERVER['REQUEST_URI'];
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
  }
