<?php

  use Thiarson\Framework\Application;

  /**
   * We use the loader provide by composer, so we don't need to manually load our classes.
   * And we load the path configuration of the project
   */

  require __DIR__.'/../vendor/autoload.php';
  $config = require __DIR__.'/../config/app.php';
  
  $app = new Application($config);
  
  $app->run();
