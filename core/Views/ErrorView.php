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

      parent::__construct('error');
    }

    public function render($view = 'error', array $params = []) {
      $viewContent = $this->renderView($view, ['exception' => $this->exception], $this->templatePath);
      $layoutContent = $this->layout($this->templatePath);

      echo str_replace('{{content}}', $viewContent, $layoutContent);
    }
  }
