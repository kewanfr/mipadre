<?php
class Model
{

  static $connections = array();

  public $table = false;
  public $db;
  public $primaryKey = 'id';

  public function __construct()
  {
    if ($this->table === false) {
      $this->table = strtolower(get_class($this)) . 's';
    }
    $conf = SecureConf::$databases[SecureConf::$dbConf];
    if (isset(Model::$connections[SecureConf::$dbConf])) {
      $this->db = Model::$connections[SecureConf::$dbConf];
      return true;
    }
    try {
      $pdo = new PDO(
        'mysql:host=' . $conf['host'] . $conf['port'] . ';dbname=' . $conf['database'] . ';',
        $conf['login'],
        $conf['password'],
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
      );

      if (Conf::$debug >= 1) {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
      }

      Model::$connections[SecureConf::$dbConf] = $pdo;
      $this->db = $pdo;
    } catch (PDOException $e) {
      if (Conf::$debug >= 1) {
        die(print_r($e->getMessage()));
      } else {
        die('Erreur lors de la connexion au système');
      }
    }
  }

  public function find($req)
  {
    $sql = 'SELECT ';

    if (isset($req['fields'])) {
      if (is_array($req['fields'])) {
        $sql .= implode(', ', $req['fields']);
      } else {
        $sql .= $req['fields'];
      }
    } else {
      $sql .= '*';
    }
    $sql .= ' FROM ' . $this->table . ' as ' . get_class($this) . ' ';


    //Construction de la condition
    if (isset($req['conditions'])) {
      //$sql .= 'WHERE '.$req['conditions'];
      $sql .= 'WHERE ';
      if (!is_array($req['conditions'])) {
        $sql .= $req['conditions'];
      } else {
        $cond = array();
        foreach ($req['conditions'] as $k => $v) {
          if (!is_numeric($v)) {
            $v = '"' . addslashes($v) . '"';
            //$cond[] = $k.'='.$v;
          }
          $cond[] = "$k=$v";
        }
        $sql .= implode(' AND ', $cond);
      }
    }
    //Construction de la condition
    if (isset($req['conditionsor'])) {
      //$sql .= 'WHERE '.$req['conditions'];
      $sql .= 'WHERE ';
      if (!is_array($req['conditionsor'])) {
        $sql .= $req['conditionsor'];
      } else {
        $cond = array();
        foreach ($req['conditionsor'] as $k => $v) {
          if (!is_numeric($v)) {
            $v = '"' . addslashes($v) . '"';
            //$cond[] = $k.'='.$v;
          }
          $cond[] = "$k=$v";
        }
        $sql .= implode(' OR ', $cond);
      }
    }


    if (isset($req['limit'])) {
      //$sql .= 'WHERE '.$req['conditions'];
      $sql .= 'LIMIT ' . $req['limit'];
    }

    $pre = $this->db->prepare($sql);
    $pre->execute();
    return $pre->fetchAll(PDO::FETCH_OBJ);
  }

  public function findCondDef()
  {
    $sql = "SELECT * FROM " . $this->table . " WHERE online=1 AND type='page'";

    //Construction de la condition
    $pre = $this->db->prepare($sql);
    $pre->execute();
    return $pre->fetchAll(PDO::FETCH_OBJ);
  }

  public function findFirst($req)
  {
    return current($this->find($req));
  }

  public function findCount($conditions)
  {
    $res = $this->findFirst(array(
      'fields' => 'COUNT(' . $this->primaryKey . ') as count',
      'conditions' => $conditions
    ));
    return $res->count;
  }

  public function delete($id)
  {
    $sql = "DELETE FROM $this->table WHERE $this->primaryKey=$id";
    $pre = $this->db->prepare($sql);
    $pre->execute();
  }

  public function save($data)
  {
    $key = $this->primaryKey;
    $fields = array();
    $d = array();
    foreach ($data as $k => $v) {
      if ($k != $this->primaryKey) {
        $fields[] = "$k=:$k";
        $d[":$k"] = $v;
      } elseif (!empty($v)) {
        $d[":$k"] = $v;
      }
    }
    if (isset($data->$key) && !empty($data->$key)) {
      $sql = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE ' . $key . '=:' . $key;
      $this->id = $data->$key;
      $action = 'update';
    } else {
      $sql = 'INSERT INTO ' . $this->table . ' SET ' . implode(', ', $fields);
      $action = 'insert';
    }
    $pre = $this->db->prepare($sql);
    $pre->execute($d);
    if ($action == 'insert') {
      $this->id = $this->db->lastInsertId();
      return $this->id;
    }
  }
}
