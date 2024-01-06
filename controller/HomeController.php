<?php

  namespace Controller;

  use Thiarson\Framework\Controller\Controller;

  class HomeController extends Controller {
    public function landing() {
      $this->render('home.landing', ['name' => 'Thiarson']);
    }
  }
