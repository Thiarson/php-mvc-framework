<?php

  namespace Thiarson\Framework;

  use Database\Models\UserModel;
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
    protected static Session $session;

    /**
     * Instance of a router.
     * 
     * @var Router
     */
    protected Router $router;
    
    /**
     * Contain all the informations about the user loged in.
     * 
     * @var UserModel
     */
    protected static ?UserModel $user;

    public function __construct(array $config) {
      self::$config = $config;
      self::$session = new Session();
      $this->router = new Router();

      $primaryValue = self::$session->get('user');

      if ($primaryValue) {
        $user = new UserModel();
        $primaryKey = $user->primaryKey();
        self::$user = $user->findOne([$primaryKey => $primaryValue]);
      }
      else {
        self::$user = null;
      }
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

    /**
     * Set the user in the session.
     * 
     * @param UserModel $user
     * @return bool
     */
    public static function login(UserModel $user) {
      self::$user = $user;
      $primaryKey = $user->primaryKey();
      $primaryValue = $user->{$primaryKey};
      self::$session->set('user', $primaryValue);

      return true;
    }

    /**
     * Remove the user in the session and logout.
     * 
     * @return void
     */
    public static function logout() {
      self::$user = null;
      // self::$session->remove('user');
      session_destroy();
      header('Location: /');

      return;
    }

    /**
     * Check if any user is loged in.
     * 
     * @return bool
     */
    public static function isGuest() {
      return !self::$user;
    }
  }
