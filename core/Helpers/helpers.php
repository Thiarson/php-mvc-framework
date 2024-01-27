<?php

  use Thiarson\Framework\Routing\Route;

  /**
   * Get the correct path associate with the specified name.
   * 
   * @param string $name
   * @param array $params
   * @return string
   */
  function route(string $name, array $params = []) {
    global $temp;
    $path = Route::getName($name)['path'];
    $temp = $params;

    if (!empty($params)) {
      if (preg_match_all("#.+({.+})#U", $path)) {
        $pattern = preg_replace_callback("#(.+)({(.+)})#U", function ($matches) {
          $replace = $GLOBALS['temp'][$matches[3]];
          return $matches[1].$replace;
        }, $path);
  
        return $pattern;
      }
    }

    return $path;
  }
