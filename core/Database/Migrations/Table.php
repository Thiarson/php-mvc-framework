<?php

  namespace Thiarson\Framework\Database\Migrations;

  class Table {
    protected array $columns;
    protected array $index;

    public function __construct() {
      $this->columns = [];
      $this->index = [];
    }

    /**
     * Create column id that integer, auto increment and primary key in the table
     * 
     * @param string $column
     */
    public function id(string $column = 'id') {
      $this->columns[] = "$column INT AUTO_INCREMENT PRIMARY KEY";
    }

    /**
     * Create column with the specified name that is varchar and not null in the table
     * 
     * @param string $column
     * @return Table
     */
    public function string(string $column) : Table {
      $this->columns[] = "$column VARCHAR(255) NOT NULL";
      return $this;
    }

    /**
     * Create column with the specified name that is timestamp and has default value the current timestamp in the table
     * 
     * @param string $column
     */
    public function timestamp(string $column) {
      $this->columns[] = "$column TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    }

    /**
     * Add unique index to the column associated in the table
     * 
     * @param string $column
     */
    public function unique() {
      $lastValue = $this->columns[sizeof($this->columns) - 1];
      $uniqueColumn = explode(' ', $lastValue);
      $uniqueColumn = $uniqueColumn[0];
      
      $this->index[] = "UNIQUE idx_uniq_$uniqueColumn ($uniqueColumn)";
    }

    /**
     * Create the SQL statement depending on the column and index specified on the context.
     * 
     * @return string 
     */
    public function statement() {
      $statement = array_merge_recursive($this->columns, $this->index);
      $statement = implode(', ', $statement);
      
      return $statement;
    }
  }
