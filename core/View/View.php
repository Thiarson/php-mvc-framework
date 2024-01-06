<?php

  namespace Thiarson\Framework\View;

  use Thiarson\Framework\Application;

  class View {
    /**
     * Path to the views folder.
     * 
     * @var string
     */
    protected string $viewsPath;

    /**
     * Layout that the view will use.
     * 
     * @var string
     */
    protected string $layout;

    public function __construct(string $layout) {
      $this->viewsPath = Application::$config['viewsPath'];
      $this->layout = $layout;
    }

    /**
     * Merge the content of the specified view in the specified content.
     * 
     * @param $view
     * @param array $params
     * @return array|string
     */
    public function render($view, array $params = []) {
      $viewContent = $this->renderView($view, $params);
      $layoutContent = $this->layout();

      return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * Get and return the content of the file in the specified layout.
     */
    protected function layout() {
      $layout = $this->layout;

      ob_start();
      include_once $this->viewsPath."/layouts/$layout.php";
      return ob_get_clean();
    }

    /**
     * Get and return the content of the file in the specified view.
     * 
     * @param $view
     * @param array $params
     * @return string|false
     */
    protected function renderView($view, array $params) {
      foreach ($params as $key => $value) {
        $$key = $value;
      }

      $view = explode('.', $view);
      $folder = $view[0];
      $view = $view[1];

      ob_start();
      include_once $this->viewsPath."/$folder/$view.php";
      return ob_get_clean();
    }
  }
