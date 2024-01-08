<?php

  namespace Thiarson\Framework\Database;

  abstract class Model {
    // Rules that can be applied to each field.
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN= 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_MATCH = 'match';
    protected const RULE_UNIQUE = 'unique';

    /**
     * Error message that correspond to each rule.
     */
    protected const ERROR_MESSAGES = [
      self::RULE_REQUIRED => 'This field is required',
      self::RULE_EMAIL => 'This field must be valid email adress',
      self::RULE_MIN => 'Min length of this field must be {min}',
      self::RULE_MAX => 'Max length of this field must be {max}',
      self::RULE_MATCH => 'This field must be the same as {match}',
      self::RULE_UNIQUE => 'Record with this {field} already exists',
    ];

    /**
     * Contains errors if any field doesn't respect the rules.
     * 
     * @var array
     */
    protected array $errors;

    /**
     * Used to define specific rules to each fields.
     */
    abstract protected function rules();

    /**
     * Load and update data into the corresponding model.
     * 
     * @param $data
     */
    public function loadData($data) {
      foreach ($data as $key => $value) {
        if(property_exists($this, $key)) {
          $this->{$key} = $value;
        }
      }
    }

    /**
     * Verify and validate eache rules specified in fields.
     * 
     * @return bool
     */
    public function validate() {
      foreach ($this->rules() as $attribute => $rules) {
        $value = $this->{$attribute};

        foreach ($rules as $rule) {
          $ruleName = $rule;

          if (!is_string($ruleName)) {
            $ruleName = $rule[0];
          }

          if ($ruleName === self::RULE_REQUIRED && !$value) {
            $this->addErrorForRule($attribute, self::RULE_REQUIRED);
          }
          else if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorForRule($attribute, self::RULE_EMAIL);
          }
          else if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
            $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
          }
          else if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
            $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
          }
          else if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
            $rule['match'] = $this->getLabel($rule['match']);
            $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
          }
          else if ($ruleName === self::RULE_UNIQUE) {
            $className = $rule['class'];
            $uniqueAttr = $rule['attribute'] ?? $attribute;
            $tableName = $className::tableName();
            $statement = Database::db()->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
            $statement->bindValue(":attr", $value);
            $statement->execute();
            $record = $statement->fetchObject();

            if ($record) {
              $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
            }
          }
        }
      }

      return empty($this->errors);
    }

    /**
     * Get the formated label corresponding to the attribute or field.
     * 
     * @param $attribute
     * @return 
     */
    public function getLabel($attribute) {
      return $this->labels()[$attribute] ?? $attribute;
    }

    /**
     * Get the first error associate to the attribute.
     * 
     * @param $attribute
     * @return 
     */
    public function getFirstError($attribute) {
      return $this->errors[$attribute][0] ?? false;
    }

    /**
     * Check if the corresponding attribute or field has an errors
     * 
     * @param $attribute
     * @return 
     */
    public function hasError($attribute) {
      return $this->errors[$attribute] ?? false;
    }

    /**
     * Add new error and message that not corresponding to all rules.
     * 
     * @param string $attribute
     * @param string $message
     */
    public function addError(string $attribute, string $message) {
      $this->errors[$attribute][] = $message;
    }

    /**
     * Add the rule that haven't respected into the errrors and the error message corresponding
     * 
     * @param string $attribute
     * @param string $rule
     * @param array $params
     */
    protected function addErrorForRule(string $attribute, string $rule, array $params = []) {
      $message = self::ERROR_MESSAGES[$rule] ?? '';

      foreach ($params as $key => $value) {
        $message = str_replace("{{$key}}", $value, $message);
      }

      $this->errors[$attribute][] = $message;
    }

    /**
     * Set formated label corresponding to each attribute.
     * 
     * @return array
     */
    protected function labels() : array {
      return [];
    }
  }
