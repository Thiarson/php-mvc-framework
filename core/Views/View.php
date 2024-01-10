<?php

  namespace Thiarson\Framework\Views;

  use Thiarson\Framework\Application;

  class View {
    /**
     * Layout that the view will use.
     * 
     * @var string
     */
    protected string $layout;

    public function __construct(string $layout = 'default') {
      $this->layout = $layout;
    }

    /**
     * Merge the content of the specified view in the specified content.
     * 
     * @param $view
     * @param array $params
     */
    public function render($view, array $params = []) {
      $viewContent = $this->renderView($view, $params);
      $layoutContent = $this->layout();

      echo str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * Change the layout of the view
     * 
     * @param string $layout
     */
    public function setLayout(string $layout) {
      $this->layout = $layout;
    }

    /**
     * Get and return the content of the file in the specified layout.
     */
    protected function layout($layoutPath = null) {
      // $layout = $this->layout;
      $layoutPath = $layoutPath !== null ? $layoutPath : Application::$config['layoutsPath'];
      $layout = $layoutPath.'/'.$this->layout.'.php';

      ob_start();
      // include_once $this->viewsPath."/layouts/$layout.php";
      include_once $layout;
      return ob_get_clean();
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
        $view = '/'.$view[0].'.php';
      }
      else {
        $folder = $view[0];
        $view = $view[1];

        $view = "/$folder/$view.php";
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
