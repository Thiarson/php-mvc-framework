<?php

  namespace Thiarson\Framework\Views;

  use Thiarson\Framework\Application;

  class View {
    /**
     * Contains all regex pattern.
     * 
     * @var array
     */
    protected static array $regex = [
      'extends' => "@extends\('([\w]+)'\)",
      'block' => "@block\('([\w]+)', '([\w]+)'\)|@block\('([\w]+)'\)(.*)@endblock",
      'bracket' => "{{(.*)}}",
      'show' => "@show\('([\w]+)'\)",
      'condition' => "@if .*@endif",
    ];

    /**
     * Contains all the new function added to the views.
     * 
     * @var array
     */
    protected static array $function = [];

    /**
     * Contains all the new global variable added to the views.
     * 
     * @var array
     */
    protected static array $global = [];

    /**
     * Intance of the layout.
     * 
     * @var Layout
     */
    protected Layout $layout;

    /**
     * Contains the layout extracted in the view.
     * 
     * @var string
     */
    protected string $extends;

    /**
     * Contains the data extracted in the view.
     * 
     * @var array
     */
    protected array $viewMatch;

    /**
     * Contains all the condition statement.
     * 
     * @var mixed
     */
    protected $condition;

    /**
     * Used in the replaceBracket() method as buffer.
     * 
     * @var mixed
     */
    protected $temp;

    public function __construct() {
      $this->layout = new Layout();
      $this->extends = '';
      $this->viewMatch = [];
      $this->condition = null;
      $this->temp = null;
    }

    /**
     * Merge the content of the specified view in the specified content.
     * 
     * @param $view
     * @param array $params
     */
    public function render($view, array $params = []) {
      $viewContent = $this->renderView($view, $params);
      $this->extractView($viewContent);
      
      $this->layout->setLayout($this->extends);
      $layoutContent = $this->layout->renderLayout();
      $view = $this->replaceLayout($layoutContent);

      echo $view;
    }

    /**
     * Get all the data in the view that need to be injected in the layout.
     * 
     * @param $viewContent
     */
    protected function extractView($viewContent) {
      $extendsRegex = self::$regex['extends'];
      $blockRegex = self::$regex['block'];

      if (preg_match_all("#$extendsRegex|$blockRegex#Us", $viewContent, $results, PREG_SET_ORDER)) {
        $this->clearResult($results);
        $this->replaceBracket($this->viewMatch);
        $this->replaceCondition($this->viewMatch);
        $this->replaceLoop($this->viewMatch);
      }
    }

    /**
     * Replace all the tags by the data extracted in the view.
     * 
     * @param $layoutContent
     */
    protected function replaceLayout($layoutContent) {
      $showRegex = self::$regex['show'];
      $conditionRegex = self::$regex['condition'];
      $bracketRegex = self::$regex['bracket'];

      $show = preg_replace_callback("#$showRegex|$conditionRegex|$bracketRegex#Us", function ($matches) {
        if (preg_match("#@if#", $matches[0])) {
          $this->replaceBracket($matches);
          $this->replaceCondition($matches);
          return $matches[0];
        }
        else if (preg_match("#{{.*}}#U", $matches[0])) {
          $temp[] = $matches[0];
          $this->replaceBracket($temp);
          return $temp[0];
        }
        else {
          return $this->viewMatch[$matches[1]];
        }
      }, $layoutContent);

      return $show;
    }

    /**
     * Replace the function or the variable in the bracket by the corresponding value.
     * 
     * @param array &$contents
     */
    protected function replaceBracket(array &$contents) {
      $bracketRegex = self::$regex['bracket'];

      foreach ($contents as $key => $value) {
        if (preg_match_all("#$bracketRegex#U", $value, $matches, PREG_SET_ORDER)) {
          foreach ($matches as $match) {
            $valueTrimed = trim($match[1]);

            if ($this->isFunction($valueTrimed)) {
              preg_match("#(.*)\((.*)\)#", $valueTrimed, $res);
              $func = $res[1].'()';

              preg_match_all("#'(.*)'#U", $res[2], $params);
              $params = $params[1];

              $this->temp = call_user_func_array(self::$function[$func], $params);
            }
            else {
              $global = explode('.', $valueTrimed);

              if (sizeof($global) > 1) {
                $param = $global[1];
                $global = $global[0];
                $this->temp = self::$global[$global][$param];
              }
              else {
                $global = $global[0];
                $this->temp = self::$global[$global];
              }
            }

            $replaced = preg_replace_callback("#$bracketRegex#U", function ($matches) {
              return $this->temp;
            }, $value);

            $contents[$key] = $replaced;
          }
        }
      }
    }
    
    /**
     * Evaluate and replace the condition statement into the corresponding value.
     * 
     * @param array &$contents
     */
    protected function replaceCondition(array &$contents) {
      $conditionRegex = self::$regex['condition'];
      $ifStatement = '@if (.*)\n(.*)(@else|@endif)';
      $elseStatement = '@else.*\n(.*)(@endif)';

      // Il faut mette en place le système de boucle imbriquée !
      foreach ($contents as $key => $value) {
        if (preg_match_all("#$conditionRegex#Us", $value, $matches, PREG_SET_ORDER)) {
          foreach ($matches as $match) {
            $temp = $match[0];
            
            while (!preg_match("#^@endif#Us", $temp)) {
              if (preg_match("#$ifStatement#Us", $temp)) {
                $temp = preg_replace_callback("#$ifStatement#Us", function ($params) {
                  $value = trim($params[1]);

                  // Il y encore beaucoup de traitement plus complexe à faire !
                  if ($this->isFunction($value)) {
                    if (call_user_func(self::$function[$value])) {
                      $this->condition = $params[2];
                      return '@endif';
                    }
                  }

                  return $params[3];
                }, $temp);
              }
              else if (preg_match("#$elseStatement#Us", $temp)) {
                $temp = preg_replace_callback("#$elseStatement#Us", function ($params) {
                  $this->condition = $params[1];
                  return $params[2];
                }, $temp);
              }
            }
            
            $replaced = preg_replace_callback("#$conditionRegex#Us", function ($matches) {
              return $this->condition;
            }, $value);

            $contents[$key] = $replaced;
          }
        }
      }
    }

    /**
     * Execute and replace the loop statement into the corresponding value.
     * 
     * @param array &$contents
     */
    protected function replaceLoop(array &$contents) {

    }

    /**
     * Retire all the empty string in the array.
     * 
     * @param array $results
     */
    protected function clearResult(array $results) {
      for ($i = 0; $i < sizeof($results); $i++) { 
        for ($j = 0, $k = 0; $j < sizeof($results[$i]); $j++) { 
          if ($results[$i][$j] !== '') {
            $cleared[$i][$k] = $results[$i][$j];
            $k++;
          }
        }
      }

      foreach ($cleared as $values) {
        if (preg_match("#^@extends#", $values[0])) {
          $this->extends = $values[1];
        }
        else {
          $this->viewMatch[$values[1]] = $values[2];
        }
      }
    }

    /**
     * Check if the param is function
     * 
     * @param string $param
     * @return bool
     */
    public function isFunction(string $param) {
      if (preg_match("#[\w]+\(.*\){1}#", $param))
        return true;

      return false;
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

      /** Il faut vérifier si le fichier existe */

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
    
    /**
     * Add new global variable to the view.
     * 
     * @param string $name
     * @param $variable
     */
    public static function addGlobal(string $name, $variable) {
      self::$global[$name] = $variable;
    }

    /**
     * Add new function to the view.
     * 
     * @param string $name
     * @param callable $callback
     */
    public static function addFunction(string $name, callable $callback) {
      self::$function["$name()"] = $callback;
    }
  }
