<?php

  namespace Thiarson\Framework\Routing;

  use Thiarson\Framework\Http\Request;

  class Router {
    /**
     * Namespace of every controller.
     * 
     * @var string
     */
    protected const CONTROLLER_NAMESPACE = "Controller\\";

    /**
     * Instance of the current request.
     *
     * @var Request
     */
    protected Request $request;

    public function __construct() {
      $this->request = new Request();
    }

    /**
     * Verify the current path with all the routes and execute the corresponding action.
     */
    public function resolve() {
      $path = $this->request->getPath();
      $method = $this->request->getMethod();
      $action = Route::getAction($method, $path);

      if ($action === null) {
        throw new \Exception('404 Not found');
      }
      else if (is_string($action)) {
        $action = $this->getController($action);
      }

      call_user_func_array($action, []);
    }

    /**
     * Extract the controller and the method in the action.
     * 
     * @param string $action
     * @return array
     */
    public function getController(string $action) {
      $action = explode('.', $action);
      $controller = self::CONTROLLER_NAMESPACE."$action[0]Controller";
      $method = $action[1];
      $controller = new $controller();

      return [$controller, $method];
    }
  }
