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
  }
