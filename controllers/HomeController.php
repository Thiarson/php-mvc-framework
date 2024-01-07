<?php

  namespace Controllers;

  use Thiarson\Framework\Controllers\Controller;

  class HomeController extends Controller {
    public function index() {
      $this->render('home.index', ['name' => 'Thiarson']);
    }
  }
