<?php

  // Here we set the database connections setup of the project.

  return [
    'connections' => [
      'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
      ],
    ]
  ];
