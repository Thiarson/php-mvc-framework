<?php

  namespace Thiarson\Framework\Database;

  abstract class DbModel extends Model {
    /**
     * Define the name of the table corresponding to the model in the database.
     * 
     * @return string
     */
    abstract public function tableName() : string;

    /**
     * Define all attributes corresponding to the model in the database.
     * 
     * @return array
     */
    abstract public function attributes() : array;

    /**
     * Define the primary key corresponding in the database.
     * 
     * @return string
     */
    abstract public function primaryKey() : string;

    /**
     * Insert the data into the specified table in the database.
     * 
     * @return bool
     */
    public function save() {
      $tableName = $this->tableName();
      $attributes = $this->attributes();
      $columns = implode(',', $attributes);

      $params = array_map(fn($attr) => ":$attr", $attributes);
      $values = implode(',', $params);

      $statement = Database::db()->prepare("INSERT INTO $tableName ($columns) VALUES ($values)");
      
      foreach ($attributes as $attribute) {
        $statement->bindValue(":$attribute", $this->{$attribute});
      }

      $statement->execute();
      return true;
    }

    /**
     * Search and find one occurance of the specified parametere in the table.
     * 
     * @param $where
     * @return 
     */
    public function findOne($where) {
      $tableName = static::tableName();
      $attributes = array_keys($where);
      $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
      $statement = Database::db()->prepare("SELECT * FROM $tableName WHERE $sql");

      foreach ($where as $key => $value) {
        $statement->bindValue(":$key", $value);
      }
      
      $statement->execute();
      return $statement->fetchObject(static::class);
    }
  }
