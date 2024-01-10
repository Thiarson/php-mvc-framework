<?php

  namespace Thiarson\Framework\Database\Migrations;

  use Thiarson\Framework\Application;
  use Thiarson\Framework\Database\Database;

  class Migration {
    /**
     * Apply one or more new migrations and save them in migrations table
     */
    public function applyMigrations() {
      $this->createMigrationsTable();
      $appliedMigrations = $this->getAppliedMigrations();

      $newMigrations = [];
      $files = scandir(Application::$config['rootDir'].'/database/migrations');
      $toApplyMigrations = array_diff($files, $appliedMigrations);

      foreach ($toApplyMigrations as $migration) {
        if($migration === '.' || $migration === '..')
          continue;

        require_once Application::$config['rootDir'].'/database/migrations/'.$migration;

        $className = pathinfo($migration, PATHINFO_FILENAME);
        $instance = new $className();

        $this->log("Applying migration $migration");
        $instance->up();
        $this->log("Applied migration $migration");

        $newMigrations[] = $migration;
      }

      if (!empty($newMigrations)) {
        $this->saveMigrations($newMigrations);
      }
      else {
        $this->log('All migrations are applied');
      }
    }

    /**
     * Creata table that will contains and records all the migrations we apply.
     */
    public function createMigrationsTable() {
      $db = Database::db();
      $db->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE = INNODB;");
    }

    /**
     * Get all migrations that we already applied in the database
     * 
     * @return array|false
     */
    public function getAppliedMigrations() {
      $db = Database::db();
      $statement = $db->prepare("SELECT migration FROM migrations");
      $statement->execute();

      return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Insert and save the migration that we recently applied in the migrations table
     * 
     * @param array $migrations
     */
    public function saveMigrations(array $migrations) {
      $db = Database::db();
      $values = implode(",", array_map(fn($m) => "('$m')", $migrations));
      $statement = $db->prepare("INSERT INTO migrations (migration) VALUES $values");
      $statement->execute();
    }
    
    /**
     * Format the message
     * 
     * @param string $message
     */
    public function log(string $message) {
      echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
  }
