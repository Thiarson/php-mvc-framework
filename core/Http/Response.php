<?php

  namespace Thiarson\Framework\Http;

  class Response {
    /**
     * Set the status code of the current response
     * 
     * @param int $code
     */
    public function setStatusCode(int $code) {
      http_response_code($code);
    }

    /**
     * Redirect to the url
     * 
     * @param string $url
     */
    public function redirect(string $url) {
      header("Location: $url");
    }
  }
