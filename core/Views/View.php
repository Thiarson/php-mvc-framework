<?php

  namespace Thiarson\Framework\Views;

  use Thiarson\Framework\Application;

  class View {
    protected static $function = [];

    /**
     * Intance of the layout.
     * 
     * @var Layout
     */
    protected Layout $layout;

    protected array $viewMatch;
    protected array $condition;
    protected array $bracket;

    public function __construct() {
      $this->layout = new Layout();
      $this->viewMatch = [];
      $this->condition = [];
      $this->bracket = [];
    }

    public static function addGlobal() {

    }

    public static function addFunction(string $name, callable $callback) {
      self::$function["$name()"] = $callback;
    }

    /**
     * Merge the content of the specified view in the specified content.
     * 
     * @param $view
     * @param array $params
     */
    public function render($view, array $params = []) {
      $viewContent = $this->renderView($view, $params);
      $viewContent = $this->replaceView($viewContent);
      $this->extends($viewContent);

      $layoutContent = $this->layout->renderLayout();

      $this->extract($viewContent);
      $view = $this->replaceLayout($layoutContent);

      echo $view;
    }

    public function extends($viewContent) {
      $extendsRegex = "@extends\('([\w]+)'\)";

      if (preg_match("#$extendsRegex#", $viewContent, $extendsMatch)) {
        $this->layout->setLayout($extendsMatch[1]);
      }
    }

    public function extract($viewContent) {
      $blockRegex = "@block\('([\w]+)', '([\w]+)'\)|@block\('([\w]+)'\)(.*)@endblock";

      if (preg_match_all("#$blockRegex#Us", $viewContent, $results, PREG_SET_ORDER)) {
        for ($i = 0; $i < sizeof($results); $i++) { 
          for ($j = 0, $k = 0; $j < sizeof($results[$i]); $j++) { 
            if ($results[$i][$j] !== '') {
              $temp[$i][$k] = $results[$i][$j];
              $k++;
            }
          }
        }
        
        foreach ($temp as $values) {
          $this->viewMatch[$values[1]] = $values[2];
        }
      }
    }

    public function replaceLayout($layoutContent) {
      $showRegex = "@show\('([\w]+)'\)";
      $conditionRegex = "@if .*@endif";

      if (preg_match_all("#$conditionRegex#Us", $layoutContent, $results, PREG_SET_ORDER)) {
        $this->condition($results);
      }

      $show = preg_replace_callback("#$showRegex|$conditionRegex#Us", function ($matches) {
        if (sizeof($matches) === 1) {
          return $this->condition[0];
        }
        else {
          return $this->viewMatch[$matches[1]];
        }
      }, $layoutContent);

      return $show;
    }

    public function replaceView($viewContent) {
      $bracketRegex = "{{(.*)}}";

      if (preg_match_all("#$bracketRegex#U", $viewContent, $results, PREG_SET_ORDER)) {
        for ($i = 0; $i < sizeof($results); $i++) { 
          $value = trim($results[$i][1]);

          if ($this->isFunction($value)) {
            preg_match("#(.*)\('(.*)'\)#", $value, $res);
            $func = $res[1].'()';
            $param = $res[2];
            
            $this->bracket[$i] = call_user_func(self::$function[$func], $param);
          }
        }
      }

      $bracket = preg_replace_callback("#{{.*}}#U", function ($matches) {
        return $this->bracket[0];
      }, $viewContent);

      return $bracket;
    }

    // Mbola tsy mety mihitsy
    public function condition($params) {
      $this->condition = [];
      $index = 0;

      foreach ($params as $param) {
        $ifStatement = '@if (.*)\n(.*)(?:@else|@endif)';
        $elseStatement = '@else.*\n(.*)(?:@else|@endif)';

        if (preg_match_all("#$ifStatement#Us", $param[0], $res, PREG_SET_ORDER)) {
          $value = trim($res[0][1]);
        
          if ($this->isFunction($value)) {
            if (call_user_func(self::$function[$value])) {
              $this->condition[$index] = $res[0][2];
              $index++;
              break;
            }
          }
        }
        
        if (preg_match_all("#$elseStatement#Us", $param[0], $res, PREG_SET_ORDER)) {
          $this->condition[$index] = $res[0][1];
          $index++;
          break;
        }        
      }
    }

    public function isFunction($param) {
      return preg_match("#.*(?:.*)#", $param, $res) ? true : false;
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
  }
