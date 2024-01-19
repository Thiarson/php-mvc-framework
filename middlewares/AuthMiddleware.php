<?php

  namespace Middlewares;

  use Database\Models\LoginModel;
  use Thiarson\Framework\Exceptions\ForbiddenException;
  use Thiarson\Framework\Http\Request;
  use Thiarson\Framework\Middlewares\Middleware;
  use Thiarson\Framework\Routing\Route;

  class AuthMiddleware extends Middleware {
    protected array $actions = [];
    protected bool $isLogged;

    /**
     * 
     * @param bool $isLogged Filter when the middleware must be executed
     */
    public function __construct(array $actions, bool $isLogged) {
      $this->actions = $actions;
      $this->isLogged = $isLogged;
    }

    public function execute() {
      if ((!$this->isLogged && !LoginModel::isLogged() || ($this->isLogged && LoginModel::isLogged()))) {
        $request = new Request();
        $path = $request->getPath();
        $method = $request->getMethod();
        $action = Route::getAction($method, $path);

        // If actions is empty, then the middleware works for all the actions
        if (empty($this->actions) || in_array($action, $this->actions)) {
          throw new ForbiddenException();
        }
      }
    }
  }
