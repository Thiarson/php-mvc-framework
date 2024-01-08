<?php

  namespace Middlewares;

  use Database\Models\UserModel;
  use Thiarson\Framework\Application;
  use Thiarson\Framework\Middlewares\Middleware;

  class UserMiddleware extends Middleware {
    public static ?UserModel $user = null;

    public function execute() {
      $primaryValue = Application::$session->get('user');

      if ($primaryValue) {
        $user = new UserModel();
        $primaryKey = $user->primaryKey();
        self::$user = $user->findOne([$primaryKey => $primaryValue]);
      }
      else {
        self::$user = null;
      }
    }
  }
