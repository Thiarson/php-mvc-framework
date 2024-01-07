<?php

  namespace Thiarson\Framework;

  use Thiarson\Framework\Http\Response;
  use Thiarson\Framework\Routing\Router;
  use Thiarson\Framework\View\View;

  class Application {
    /**
     * Will contain the configuration of our environnement.
     * 
     * @var array
     */
    public static array $config;

    /**
     * Instance of a router.
     * 
     * @var Router
     */
    protected Router $router;

    public function __construct(array $config) {
      self::$config = $config;
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
        $view = new View();

        $response->setStatusCode($e->getCode());
        $view->render('error', ['exception' => $e]);
      }
    }
  }
