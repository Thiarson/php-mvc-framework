<?php

  namespace Thiarson\Framework\Routing;

  use Thiarson\Framework\Exceptions\NotFoundException;
  use Thiarson\Framework\Http\Request;
  use Thiarson\Framework\Http\Response;

  class Router {
    /**
     * Namespace of every controller.
     * 
     * @var string
     */
    protected const CONTROLLER_NAMESPACE = "Controllers\\";

    /**
     * Instance of the current request.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Instance of the current response.
     *
     * @var Response
     */
    protected Response $response;

    public function __construct() {
      $this->request = new Request();
      $this->response = new Response();
    }

    /**
     * Verify the current path with all the routes and execute the corresponding action.
     */
    public function resolve() {
      $path = $this->request->getPath();
      $method = $this->request->getMethod();
      $action = Route::getAction($method, $path);

      if ($action === null) {
        throw new NotFoundException();
      }
      else if (is_string($action)) {
        $action = $this->getController($action);
        $controller = $action[0];
        
        foreach ($controller->getMiddlewares() as $middleware) {
          $middleware->execute();
        }
      }

      call_user_func($action, $this->request, $this->response);
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
