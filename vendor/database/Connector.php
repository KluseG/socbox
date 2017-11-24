<?php

namespace vendor\database;

use vendor\dotenv\Dotenv;
use PDO;

class Connector {

  private static $instance = null;
  private $conn;
  private $query;

  private $host;
  private $user;
  private $pass;
  private $name;

  private function __construct() {
    $this->host = getenv('DB_HOST');
    $this->user = getenv('DB_USER');
    $this->pass = getenv('DB_PASSWORD');
    $this->name = getenv('DB_NAME');

    $this->conn = new PDO("mysql:host={$this->host}; dbname={$this->name}", $this->user,$this->pass);
    $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }

  public static function getInstance() {
    if(!self::$instance) {
      self::$instance = new Connector();
    }

    return self::$instance;
  }

  public function getConnection() {
    return $this->conn;
  }

  public function setQuery($sql) {
    try {
        $this->query = $this->conn->prepare($sql);
    }
    catch (PDOException $err) {
      return ['query' => null, 'fetch' => null, 'error' => $err];
    }

    $this->query->execute();

    if (strpos($sql, 'SELECT') !== false) {
      $fetch = $this->query->fetchAll(PDO::FETCH_ASSOC);
    }
    else if (strpos($sql, 'INSERT') !== false) {
      $fetch = $this->conn->lastInsertId();
    }
    else {
      $fetch = null;
    }

    return ['query' => true, 'fetch' => $fetch, 'error' => null];
  }

  public function close() {
    $this->query->closeCursor();
    $this->query = null;
    $this->conn = null;

    return true;
  }
}

?>
