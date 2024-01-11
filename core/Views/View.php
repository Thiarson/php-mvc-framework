<?php

  namespace Thiarson\Framework\Views;

  use Thiarson\Framework\Application;

  class View {
    /**
     * Intance of the layout.
     * 
     * @var Layout
     */
    protected Layout $layout;

    public function __construct(string $layout = 'default') {
      $this->layout = new Layout($layout);
    }

    /**
     * Merge the content of the specified view in the specified content.
     * 
     * @param $view
     * @param array $params
     */
    public function render($view, array $params = []) {
      $viewContent = $this->renderView($view, $params);
      $layoutContent = $this->layout->renderLayout();

      echo str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * Get and return the content of the file in the specified view.
     * 
     * @param $view
     * @param array $params
     * @return string|false
     */
    protected function renderView($view, array $params, $viewPath = null) {
      foreach ($params as $key => $value) {
        $$key = $value;
      }

      $view = explode('.', $view);

      if (sizeof($view) === 1) {
        $view = '/'.$view[0].'.view.php';
      }
      else {
        $folder = $view[0];
        $view = $view[1];

        $view = "/$folder/$view.view.php";
      }

      $viewPath = $viewPath !== null ? $viewPath : Application::$config['viewsPath'];

      ob_start();
      // include_once $this->viewsPath.$view;
      include_once $viewPath.$view;
      return ob_get_clean();
    }

    /**
     * Render directly the view with the specified layout.
     * 
     * @param string $viewName
     * @param string $layout
     */
    public static function view(string $viewName, string $layout = 'default') {
      $view = new View($layout);
      $view->render($viewName);
    }
  }
