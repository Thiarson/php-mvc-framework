<?php

  use Thiarson\Framework\Database\Migrations\Schema;
  use Thiarson\Framework\Database\Migrations\Table;

  class m0001_create_users_table {
    /**
     * Run the migrations.
     */
    public function up() {
      Schema::create('users', function (Table $table) {
        $table->id();
        $table->string('email')->unique();
        $table->string('name');
        $table->string('password');
        $table->timestamp('created_at');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
      Schema::dropIfExists('users');
    }
  }
