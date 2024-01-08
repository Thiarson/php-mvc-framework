<?php

  namespace Thiarson\Framework;

  use Thiarson\Framework\Http\Response;
  use Thiarson\Framework\Routing\Router;
  use Thiarson\Framework\Session\Session;
  use Thiarson\Framework\Views\View;

  class Application {
    /**
     * Will contain the configuration of our environnement.
     * 
     * @var array
     */
    public static array $config;

    /**
     * Contain all the sessions of the project.
     * 
     * @var Session
     */
    public static Session $session;

    /**
     * Instance of a router.
     * 
     * @var Router
     */
    protected Router $router;

    public function __construct(array $config) {
      self::$config = $config;
      self::$session = new Session();
      $this->router = new Router();
    }

    /**
     *  Initialize the application and resolve the current path.
     */
    public function run() {
      try {
        $this->router->resolve();
      }
      catch (\Exception $e) {
        $response = new Response();
        $view = new View('auth');

        $response->setStatusCode($e->getCode());
        $view->render('error', ['exception' => $e]);
      }
    }
  }
