<?php

  /**
   * Get the specified value in the dot env.
   * 
   * @param string $value
   * @return string
   */
  function env(string $value) : string {
    return $_ENV[$value];
  }
