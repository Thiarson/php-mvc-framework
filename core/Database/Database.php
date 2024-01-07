<?php

  namespace Thiarson\Framework\Database;

  use Thiarson\Framework\Application;

  class Database {
    /**
     * Instance of PDO.
     * 
     * @var \PDO
     */
    protected \PDO $pdo;

    /**
     * Instance of database.
     */
    protected static ?Database $db = null;

    protected function __construct() {
      try {
        $dsn = $_ENV['DB_CONNECTION'].':host='.$_ENV['DB_HOST'].';port='.$_ENV['DB_PORT'].';dbname='.$_ENV['DB_DATABASE'];
        $options = array(
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        );

        $this->pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
      }
      catch (\PDOException $e) {
        echo $e->getMessage();
      }
    }

    /**
     * Get the instance of the database.
     * 
     * @return Database $db
     */
    public static function db() {
      if (is_null(self::$db))
        self::$db = new Database();

      return self::$db;
    }

    /**
     * Prepares a statement for execution and returns a statement object
     * 
     * @param string $sql
     * @return PDOStatement|false
     */
    public function prepare(string $sql) {
      return $this->pdo->prepare($sql);
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     * 
     * @param string $sql
     * @return int|false
     */
    public function exec(string $sql) {
      return $this->pdo->exec($sql);
    }

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
      $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
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
      $statement = $this->pdo->prepare("SELECT migration FROM migrations");
      $statement->execute();

      return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Insert and save the migration that we recently applied in the migrations table
     * 
     * @param array $migrations
     */
    public function saveMigrations(array $migrations) {
      $values = implode(",", array_map(fn($m) => "('$m')", $migrations));
      $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");
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
