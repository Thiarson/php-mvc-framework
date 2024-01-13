<?php

  namespace Thiarson\Framework\Views;

  use Thiarson\Framework\Application;
  use Thiarson\Framework\Http\Response;

  class ErrorView extends View {
    protected \Exception $exception;
    protected string $templatePath;

    public function __construct(\Exception $e) {
      $this->exception = $e;
      $response = new Response();
      $response->setStatusCode($e->getCode());
      $this->templatePath = Application::$config['rootDir'].'/core/Templates/Error';

      parent::__construct();
    }

    public function render($view = 'error', array $params = []) {
      $viewContent = $this->renderView($view, ['exception' => $this->exception], $this->templatePath);
      $this->extractView($viewContent);
      
      $this->layout->setLayout($this->extends);
      $layoutContent = $this->layout->renderLayout($this->templatePath);
      $view = $this->replaceLayout($layoutContent);

      echo $view;
    }
  }
