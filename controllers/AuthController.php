<?php

  namespace Controllers;

  use Database\Models\LoginModel;
  use Middlewares\UserMiddleware;
  use Thiarson\Framework\Controllers\Controller;
  use Thiarson\Framework\Http\Request;
  use Thiarson\Framework\Http\Response;

  class AuthController extends Controller {
    public function __construct() {
      $middlewares = [
        new UserMiddleware(),
      ];
      $this->registerMiddleware($middlewares);
    }

    public function login(Request $request, Response $response) {
      $loginModel = new LoginModel;
      
      if ($request->isPost()) {
        $loginModel->loadData($request->getBody());

        if ($loginModel->validate() && $loginModel->login()) {
          $response->redirect('/home');
          return;
        }
      }

      $this->render('auth.login', [
        'model' => $loginModel,
      ], 'auth');
    }

    public function logout() {
      LoginModel::logout();
    }
  }
