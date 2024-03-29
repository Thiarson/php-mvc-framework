<?php

  use Thiarson\Framework\Application;

  /**
   * We use the loader provide by composer, so we don't need to manually load our classes.
   * And we load the path configuration of the project
   */

  require __DIR__.'/../vendor/autoload.php';

  // Load the path configuration in the configuration file
  $config = require __DIR__.'/../config/app.php';

  // Load all the configuration in the .env file
  $dotenv = Dotenv\Dotenv::createImmutable($config['rootDir']);
  $dotenv->load();
  
  $app = new Application($config);
  
  $app->run();
