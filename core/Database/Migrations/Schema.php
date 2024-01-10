<?php

  namespace Thiarson\Framework\Database\Migrations;

  use Thiarson\Framework\Database\Database;

  class Schema {
    /**
     * Create the specified table in the database
     * 
     * @param string $tableName
     * @param callable $callback
     */
    public static function create(string $tableName, callable $callback) {
      $db = Database::db();
      $table = new Table();

      call_user_func($callback, $table);
      $statement = $table->statement();
      $sql = "CREATE TABLE IF NOT EXISTS $tableName ($statement) ENGINE = INNODB;";
      $db->exec($sql);
    }

    /**
     * Drop the specified table if exists in the database
     * 
     * @param string $tableName
     */
    public static function dropIfExists(string $tableName) {
      $db = Database::db();
      $sql = "DROP TABLE IF EXISTS $tableName;";
      $db->exec($sql);
    }
  }
