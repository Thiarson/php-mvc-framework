<?php

  namespace Thiarson\Framework\Templates\Form;

  use Thiarson\Framework\Database\Model;

  class InputField extends Field {
    // All the possible type that the input can be.
    protected const TYPE_TEXT = 'text';
    protected const TYPE_PASSWORD = 'password';
    protected const TYPE_EMAIL = 'email';

    /**
     * The type of the current input.
     * 
     * @var string
     */
    protected string $type;

    public function __construct(Model $model, string $attribute) {
      $this->type = self::TYPE_TEXT;
      parent::__construct($model, $attribute);
    }

    /**
     * Set the type of the current input as password.
     * 
     * @return InputField
     */
    public function passwordField() {
      $this->type = self::TYPE_PASSWORD;
      return $this;
    }

    /**
     * Set the type of the current input as email.
     * 
     * @return InputField
     */
    public function emailField() {
      $this->type = self::TYPE_EMAIL;
      return $this;
    }

    /**
     * Render and set the correspondig attribute to the input field.
     * 
     * @return string
     */
    public function renderField() : string {
      return sprintf('<input type="%s" name="%s" value="%s" style="%s">',
        $this->type,
        $this->attribute,
        $this->model->{$this->attribute},
        $this->model->hasError($this->attribute) ? 'border-color: red;' : '',
      );
    }
  }
