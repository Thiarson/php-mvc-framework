<?php

  use Thiarson\Framework\Database\Database;

  class m0002_insert_data_to_users_table {
    public function up() {
      $db = Database::db();
      $password = password_hash('admin', PASSWORD_DEFAULT);
      $db->exec('INSERT INTO users (name, email, password) VALUES ("Admin", "admin@gmail.com", "'.$password.'")');
    }

    public function down() {
      $db = Database::db();
      $db->exec('DELETE FROM users WHERE email = "admin@gmail.com";');
    }
  }
