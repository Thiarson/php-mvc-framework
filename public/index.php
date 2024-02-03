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

  // Load the database information in the configuration file
  $db = require __DIR__.'/../config/database.php';

  $config['database'] = $db['connections'][env('DB_CONNECTION')];
  
  $app = new Application($config);
  
  $app->run();
