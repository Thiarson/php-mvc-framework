<?php

  use Thiarson\Framework\Application;

  // We use the loader provide by composer, so we don't need to manually load our classes.

  require __DIR__.'/../vendor/autoload.php';
  
  $app = new Application();
  
  $app->run();
