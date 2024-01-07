<?php

  use Thiarson\Framework\Database\Database;

  class m0001_create_users_table {
    /**
     * Run the migrations.
     */
    public function up() {
      $db = Database::db();
      $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        firstname VARCHAR(255) NOT NULL,
        lastname VARCHAR(255) NOT NULL,
        status TINYINT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE = INNODB;";
      $db->exec($sql);      
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
      $db = Database::db();
      $sql = "DROP TABLE IF EXISTS users;";
      $db->exec($sql);
    }
  }