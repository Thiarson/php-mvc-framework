<?php

  use Database\Models\LoginModel;
  use Thiarson\Framework\Application;
  use Thiarson\Framework\Views\View;

  // Here we add function or global variable to the views.

  View::addFunction('isLogged', function () {
    return LoginModel::isLogged();
  });

  View::addFunction('session', function (string $param) {
    return Application::$session->get($param);
  });
