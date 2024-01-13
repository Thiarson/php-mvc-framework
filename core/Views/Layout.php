<?php

  namespace Thiarson\Framework\Views;

  use Thiarson\Framework\Application;

  class Layout {
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
    public function renderLayout($layoutPath = null) {
      $layoutPath = $layoutPath !== null ? $layoutPath : Application::$config['layoutsPath'];
      $layout = $layoutPath.'/'.$this->layout.'.layout.php';

      /** Il faut d'abord vérifier si le fichier existe */

      ob_start();
      include_once $layout;
      return ob_get_clean();
    }
  }
