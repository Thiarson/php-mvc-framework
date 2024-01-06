<?php

  namespace Thiarson\Framework\core\Routing;

  use Thiarson\Framework\core\Http\Request;

  class Router {
    protected const CONTROLLER_NAMESPACE = "Thiarson\Framework\controller\\";

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
      $controller = self::CONTROLLER_NAMESPACE."$action[0]";
      $method = $action[1];
      $controller = new $controller();

      return [$controller, $method];
    }
  }
