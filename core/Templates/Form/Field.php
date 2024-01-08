<?php

  namespace Thiarson\Framework\Templates\Form;

  use Thiarson\Framework\Database\Model;

  abstract class Field {
    /**
     * Used to render specific field in extended class.
     * 
     * @return string
     */
    abstract public function renderField() : string;

    /**
     * Instance of model
     * 
     * @var Model
     */
    protected Model $model;

    /**
     * Instance of attribute
     * 
     * @var string
     */
    protected string $attribute;

    public function __construct(Model $model, string $attribute) {
      $this->model = $model;
      $this->attribute = $attribute;
    }

    public function __toString() {
      return sprintf('
        <div>
          <label>%s </label>
          %s
          <div style="color: red;">
            %s
          </div>
        </div>
        ',
        $this->model->getLabel($this->attribute),
        $this->renderField(),
        $this->model->getFirstError($this->attribute)
      );
    }
  }
