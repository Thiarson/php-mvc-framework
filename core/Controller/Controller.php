<?php

  namespace Thiarson\Framework\Controller;

  use Thiarson\Framework\View\View;

  class Controller {
    /**
     * Implement the render method based on the view render, so every controller that extends this base controller will have this method.
     * 
     * @param $view
     * @param array $params
     * @param string $layout
     */
    public function render($view, array $params = [], string $layout = 'default') {
      $views = new View($layout);
      $views->render($view, $params);
    }
  }
