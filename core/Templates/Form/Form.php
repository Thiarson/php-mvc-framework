<?php

  namespace Thiarson\Framework\Templates\Form;

  use Thiarson\Framework\Database\Models\Model;

  class Form {
    protected array $fields;

    public function __construct() {
      $this->fields = [];
    }
    
    /**
     * Set the begining of the form balise.
     * 
     * @param string $method
     * @param string $action
     * @return Form
     */
    public function begin(string $method, string $action) {
      echo sprintf('<form method="%s" action="%s">', $method, $action);
    }

    /**
     * Set the end of the form balise and the submit input.
     * 
     * @param string $value
     * @param string $type
     */
    public function end( string $value = 'Submit', string $type = 'submit') {
      foreach ($this->fields as $field) {
        $field->render();
      }
      
      echo sprintf('
        <input type="%s" value="%s"/>
        </form>
      ', $type, $value);
    }

    /**
     * Create an input field.
     * 
     * @param Model $model
     * @param $attribute
     * @return InputField
     */
    public function field(Model $model, $attribute) {
      $input = new InputField($model, $attribute);
      $this->fields[] = $input;

      return $input;
    }
  }
