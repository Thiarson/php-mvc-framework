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
        $db = Application::$config['database'];
        $dsn = $db['driver'].':host='.$db['host'].';port='.$db['port'].';dbname='.$db['database'];
        $options = array(
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        );

        $this->pdo = new \PDO($dsn, $db['username'], $db['password'], $options);
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
