<?php

  namespace Database\Models;

  use Thiarson\Framework\Application;
  use Thiarson\Framework\Database\Models\Model;

  class LoginModel extends Model {
    public string $email = '';
    public string $password = '';

    public function rules() : array {
      return [
        'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
        'password' => [self::RULE_REQUIRED],
      ];
    }

    public function labels() : array {
      return [
        'email' => 'Your email',
        'password' => 'Password',
      ];
    }

    public function login() {
      $user = new UserModel();
      $user = $user->findOne(['email' => $this->email]);

      if (!$user) {
        $this->addError('email', 'User does not exist with this email');
        return false;
      }

      if (!password_verify($this->password, $user->password)) {
        $this->addError('password', 'Password is incorrect');
        return false;
      }

      Application::$session->set('user',$user->name);

      return true;
    }

    public static function logout() {
      // self::$session->remove('user');
      session_destroy();
      header('Location: /');

      return;
    }

    public static function isLogged() {
      $session = Application::$session->get('user');
      return !$session ? false : true;
    }
  }
