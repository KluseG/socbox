<?php

namespace vendor\model;

use vendor\database\Connector;

class ModelWrapper {
  protected $db;
  public $table;

  public $queryString;

  public function __construct() {
    $this->db = Connector::getInstance();
  }

  public function save() {
    if (!$this->db) {
      $this->db = Connector::getInstance();
    }
    $vars = get_object_vars($this);
    $varsLength = count($vars);
    $columns = [];
    $values = [];

    $i = 0;
    foreach ($vars as $column => $value) {
      $i++;
      if ($column != 'db' &&
          $column != 'table' &&
          $column != 'queryString' &&
          $column != 'currentId') {

            $columns[] = $column;
            $values[] = '"' . $value . '"';
          }
    }

    $columns = implode($columns, ',');
    $values = implode($values, ',');

    $sql = 'INSERT INTO ' . $this->table . '(' . $columns . ') VALUES (' . $values . ')';

    try {
      $this->currentId = $this->db->setQuery($sql);
      $this->currentId = $this->currentId['fetch'];
    }
    catch (PDOException $err) {
      echo $err;
      die();

      return false;
    }

    return true;
  }

  public function all() {
    if (!$this->db) {
      $this->db = Connector::getInstance();
    }

    if (!isset($this->queryString['columns'])) {
      $this->queryString['columns'] = '*';
    }

    $query = $this->db->setQuery('SELECT '. $this->queryString['columns'] .' FROM ' . $this->table);
    $fetch = $query['fetch'];
    $error = $query['error'];

    return $fetch ?? $error;
  }

  public function where($col, $val, $op = null) {
    $localQuery[0] = $this->table . '.' . $col;
    $localQuery[1] = $val;

    if ($col == 'id') {
      $this->currentId = $val;
    }

    if ($op) {
      $localQuery[2] = $op;
    }

    $localString['query'] = ['type' => 'WHERE', 'value' => $localQuery, 'order' => 999];

    $this->queryString[] = $localString;

    return $this;
  }

  public function select($cols) {
    $this->queryString['columns'] = $cols;

    return $this;
  }

  public function get($debug = false) {
    if (!$this->db) {
      $this->db = Connector::getInstance();
    }

    if (!isset($this->queryString['columns'])) {
      $this->queryString['columns'] = '*';
    }

    $columns = $this->queryString['columns'];

    unset($this->queryString['columns']);

    usort($this->queryString, function($a, $b) {
      if (isset($a['query']) && isset($b['query'])) {
        return ($a['query']['order'] < $b['query']['order']) ? -1 : 1;
      }
      else {
        return 0;
      }
    });

    $finalQuery = 'SELECT ' .$columns. ' FROM ' .$this->table;

    $whereCounter = 0;

    foreach ($this->queryString as $key => $item) {
      if ($key !== 'columns') {
        if ($item['query']['type'] == 'WHERE') {
          $whereCounter++;
          if ($whereCounter > 1) {
            $item['query']['type'] = 'AND';
          }

          $op = '=';

          if (isset($item['query']['value'][2])) {
            $op = $item['query']['value'][2];
          }

          $finalQuery .= ' ' . $item['query']['type'] . ' ' . $item['query']['value'][0] . ' ' . $op . ' ' . '"'.$item['query']['value'][1].'"';
        }
        if ($item['query']['type'] == 'INNER JOIN') {
          $finalQuery .= ' INNER JOIN ' . $item['query']['value'][0] . ' ON ' . $item['query']['value'][1];
        }
        if ($item['query']['type'] == 'ORDER BY') {
          $finalQuery .= ' ORDER BY ' . $item['query']['value'][0] . ' ' . $item['query']['value'][1];
        }
      }
    }

    if ($debug) {
      return $finalQuery;
    }

    $query = $this->db->setQuery($finalQuery);
    $fetch = $query['fetch'];
    $error = $query['error'];

    if ($fetch) {
      if (count($fetch) == 1) {
        $this->currentId = $fetch[0]['id'] ?? null;
        return $fetch[0];
      }
      else {
        return $fetch;
      }
    }
    else {
      return $error;
    }
  }

  public function set($col, $val) {
    $localQuery[0] = $col;
    $localQuery[1] = $val;

    $localString['query'] = ['type' => 'SET', 'value' => $localQuery, 'order' => 998];

    $this->queryString[] = $localString;

    return $this;
  }

  public function update() {
    if (!$this->db) {
      $this->db = Connector::getInstance();
    }

    $finalQuery = 'UPDATE ' . $this->table . ' SET ';

    foreach ($this->queryString as $key => $item) {
      if ($key !== 'columns') {
        if ($item['query']['type'] == 'SET') {
            $localString[] = $item['query']['value'][0] . '="' . $item['query']['value'][1] . '"';
        }
      }
    }

    $finalQuery .= implode($localString, ',');

    $whereCounter = 0;

    foreach ($this->queryString as $key => $item) {
      if ($key !== 'columns') {
        if ($item['query']['type'] == 'WHERE') {
          $whereCounter++;
          if ($whereCounter > 1) {
            $item['query']['type'] = 'AND';
          }

          $op = '=';

          if (isset($item['query']['value'][2])) {
            $op = $item['query']['value'][2];
          }

          $finalQuery .= ' ' . $item['query']['type'] . ' ' . $item['query']['value'][0] . ' ' . $op . ' ' . '"'.$item['query']['value'][1].'"';
        }
      }
    }

    $query = $this->db->setQuery($finalQuery);
    $fetch = $query['fetch'];
    $error = $query['error'];

    if ($fetch) {
      if (count($fetch) == 1) {
        return $fetch[0];
      }
      else {
        return $fetch;
      }
    }
    else {
      return $error;
    }
  }

  public function delete() {
    if (!$this->db) {
      $this->db = Connector::getInstance();
    }

    $finalQuery = 'DELETE FROM ' . $this->table;

    $whereCounter = 0;

    foreach ($this->queryString as $key => $item) {
      if ($key !== 'columns') {
        if ($item['query']['type'] == 'WHERE') {
          $whereCounter++;
          if ($whereCounter > 1) {
            $item['query']['type'] = 'AND';
          }

          $op = '=';

          if (isset($item['query']['value'][2])) {
            $op = $item['query']['value'][2];
          }

          $finalQuery .= ' ' . $item['query']['type'] . ' ' . $item['query']['value'][0] . ' ' . $op . ' ' . '"'.$item['query']['value'][1].'"';
        }
      }
    }

    $query = $this->db->setQuery($finalQuery);
    $fetch = $query['fetch'];
    $error = $query['error'];

    if ($fetch) {
      if (count($fetch) == 1) {
        return $fetch[0];
      }
      else {
        return $fetch;
      }
    }
    else {
      return $error;
    }
  }

  public function orderBy($by, $how) {
    $localQuery[0] = $by;
    $localQuery[1] = $how;

    $localString['query'] = ['type' => 'ORDER BY', 'value' => $localQuery, 'order' => 1000];

    $this->queryString[] = $localString;

    return $this;
  }

  public function hasOne($name, $use = null, $join = null) {
    $mdl = 'app\\models\\'.$name;
    $mdl = new $mdl;

    if (!$use) {
      $localQuery[0] = $this->table . '_id';
    }
    else {
      $localQuery[0] = $use;
    }

    if (!$join) {
      $localQuery[1] = isset($this->currentId) ? $this->currentId : $this->get()['id'];
    }
    else {
      $localQuery[1] = $this->get()[$join];
    }

    $localString['query'] = ['type' => 'WHERE', 'value' => $localQuery, 'order' => 999];

    $mdl->queryString[] = $localString;

    return $mdl;
  }

  public function hasMany($table, $aggr, $use = null, $join = null, $third = null) {
    $base = 'app\\models\\'.$table;
    $base = new $base;

    $aggr = 'app\\models\\'.$aggr;
    $aggr = new $aggr;


    if (!$use) {
      $aggrQuery[0] = $aggr->table . '.' . $this->table . '_id';
    }
    else {
      $aggrQuery[0] = $aggr->table . '.' . $use;
    }

    $aggrQuery[1] = $this->currentId ?? $this->get()['id'];

    $aggrString['query'] = ['type' => 'WHERE', 'value' => $aggrQuery, 'order' => 999];
    $aggr->queryString[] = $aggrString;

    $aggrQuery[0] = $base->table;

    if (!$join) {
      $aggrQuery[1] = $base->table . '.id = ' . $aggr->table . '.' . $base->table . '_id';
    }
    else {
      $aggrQuery[1] = $base->table . '.id = ' . $aggr->table . '.' . $join;
    }

    $aggrString['query'] = ['type' => 'INNER JOIN', 'value' => $aggrQuery, 'order' => 1];
    $aggr->queryString[] = $aggrString;

    if ($base->table !== $this->table) {
      $aggrQuery[0] = $this->table;

      if (!$use) {
        $aggrQuery[1] = $this->table . '.id = ' . $aggr->table . '.' . $this->table . '_id';
      }
      else {
        $aggrQuery[1] = $this->table . '.id = ' . $aggr->table . '.' . $use;
      }

      $aggrString['query'] = ['type' => 'INNER JOIN', 'value' => $aggrQuery, 'order' => 2];
      $aggr->queryString[] = $aggrString;
    }

    if ($third) {
      $third = 'app\\models\\'.$third;
      $third = new $third;

      $aggrQuery[0] = $third->table;

      if (!$use) {
        $aggrQuery[1] = $third->table . '.id = ' . $aggr->table . '.' . $third->table . '_id';
      }
      else {
        $aggrQuery[1] = $third->table . '.id = ' . $aggr->table . '.' . $use;
      }

      $aggrString['query'] = ['type' => 'INNER JOIN', 'value' => $aggrQuery, 'order' => 3];
      $aggr->queryString[] = $aggrString;
    }

    return $aggr;
  }
}

?>
