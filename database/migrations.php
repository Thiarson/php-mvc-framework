<?php

  use Thiarson\Framework\Application;
  use Thiarson\Framework\Database\Migrations\Migration;

  require __DIR__.'/../vendor/autoload.php';
  $config = require __DIR__.'/../config/app.php';

  $dotenv = Dotenv\Dotenv::createImmutable($config['rootDir']);
  $dotenv->load();
  
  // Execute and save all new migrations
  $app = new Application($config);
  $migration = new Migration();

  $migration->applyMigrations();
