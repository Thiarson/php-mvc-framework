<?php

  namespace Thiarson\Framework\Database;

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
  }
