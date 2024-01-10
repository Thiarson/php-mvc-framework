<?php

  namespace Thiarson\Framework\Database\Migrations;

  class Table {
    protected array $columns;
    protected array $index;

    public function __construct() {
      $this->columns = [];
      $this->index = [];
    }

    public function id(string $column = 'id') {
      $this->columns[] = "$column INT AUTO_INCREMENT PRIMARY KEY";
    }

    public function string(string $column) : Table {
      $this->columns[] = "$column VARCHAR(255) NOT NULL";
      return $this;
    }

    public function timestamp(string $column) {
      $this->columns[] = "$column TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    }

    public function unique() {
      $lastValue = $this->columns[sizeof($this->columns) - 1];
      $uniqueColumn = explode(' ', $lastValue);
      $uniqueColumn = $uniqueColumn[0];
      
      $this->index[] = "UNIQUE idx_uniq_$uniqueColumn ($uniqueColumn)";
    }

    public function statement() {
      $statement = array_merge_recursive($this->columns, $this->index);
      $statement = implode(', ', $statement);
      
      return $statement;
    }
  }
