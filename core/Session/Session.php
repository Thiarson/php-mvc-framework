<?php

  namespace Thiarson\Framework\Session;

  class Session {
    public function __construct() {
      session_start();
    }

    /**
     * Set the key and value in the session.
     * 
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
      $_SESSION[$key] = $value;
    }

    /**
     * Get the value corresponding to the key in the session.
     * 
     * @param $key
     * @return 
     */
    public function get($key) {
      return $_SESSION[$key] ?? false;
    }
    
    /**
     * Remove the key and value corresponding to the key in the session.
     * 
     * @param $key
     */
    public function remove($key) {
      unset($_SESSION[$key]);
    }
  }
