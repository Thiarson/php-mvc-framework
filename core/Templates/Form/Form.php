<?php

  namespace Thiarson\Framework\Templates\Form;

  use Thiarson\Framework\Database\Model;

  class Form {
    /**
     * Set the begining of the form balise.
     * 
     * @param string $method
     * @param string $action
     * @return Form
     */
    public static function begin(string $method, string $action) {
      echo sprintf('<form method="%s" action="%s">', $method, $action);
      return new Form();
    }

    /**
     * Set the end of the form balise and the submit input.
     * 
     * @param string $value
     * @param string $type
     */
    public static function end( string $value = 'Submit', string $type = 'submit') {
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
      return new InputField($model, $attribute);
    }
  }
