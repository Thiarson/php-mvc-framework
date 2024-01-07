<?php

  use Thiarson\Framework\Database\Database;

  class m0002_add_password_column_to_users_table {
    public function up() {
      $db = Database::db();
      $db->exec("ALTER TABLE users ADD COLUMN password VARCHAR(512) NOT NULL;");
    }

    public function down() {
      $db = Database::db();
      $db->exec("ALTER TABLE users DROP COLUMN password;");
    }
  }
