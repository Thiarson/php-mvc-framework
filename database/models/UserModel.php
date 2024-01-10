<?php

  namespace Database\Models;

  use Thiarson\Framework\Database\Models\DbModel;

  class UserModel extends DbModel {
    public int $id = 0;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public string $created_at = '';

    public function rules() : array {
      return [
        'name' => [self::RULE_REQUIRED],
        'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
        'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => '6']],
        'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
      ];
    }

    public function save() {
      $this->password = password_hash($this->password, PASSWORD_DEFAULT);
      return parent::save();
    }

    public function tableName(): string {
      return 'users';
    }

    public function primaryKey(): string {
      return 'id';
    }
    
    public function attributes(): array {
      return ['firstname', 'lastname', 'email', 'password'];
    }

    public function labels() : array {
      return [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'confirmPassword' => 'Confirm Password',
      ];
    }
  }
