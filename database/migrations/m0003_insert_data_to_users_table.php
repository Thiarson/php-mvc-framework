<?php

  use Thiarson\Framework\Database\Database;

  class m0003_insert_data_to_users_table {
    public function up() {
      $db = Database::db();
      // $db->exec("ALTER TABLE users ADD COLUMN password VARCHAR(512) NOT NULL;");
      $password = password_hash('0123456', PASSWORD_DEFAULT);
      $db->exec('INSERT INTO users (firstname, lastname, email, password) VALUES ("Thiarson", "Antsa", "thiarsonantsa@gmail.com", "'.$password.'")');
    }

    public function down() {
      
    }
  }
