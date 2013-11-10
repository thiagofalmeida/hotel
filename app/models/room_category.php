<?php class RoomCategory extends Base {
    private $name;

    public function setName($name) {
      $this->name = $name;
    }
    public function getName() {
      return $this->name;
    }

    public function validates() {
      Validations::notEmpty($this->name, 'name', $this->errors);
      /* Como o campo é único é necessário atualizar caso não tenha mudado*/
      if ($this->newRecord() || $this->changedFieldValue('name', 'roomCategories')) {
        Validations::uniqueField($this->name, 'name', 'roomCategories', $this->errors);
      }
    }

    public function save() {
      if (!$this->isvalid()) return false;

      $sql = "INSERT INTO roomCategories (name, created_at) values ($1, $2) RETURNING ID;";
      $params = array($this->name, $this->createdAt);
      $db_conn = Database::getConnection();
      $resp = pg_query_params($db_conn, $sql, $params);

      if (!$resp) {
        Logger::getInstance()->log("Falha para salvar o objeto: " . print_r($this, TRUE), Logger::ERROR);
        Logger::getInstance()->log("Error: " . print_r(error_get_last(), true), Logger::ERROR);
        return false;
      }

      $row = pg_fetch_assoc($resp);
      $this->setId($row['id']);
      return true;
    }

    public function update($data = array()) {
      $this->setData($data);
      if (!$this->isvalid()) return false;

      $db_conn = Database::getConnection();
      $params = array($this->name, $this->id);
      $sql = "update roomCategories set name=$1 where id = $2";

      return pg_query_params($db_conn, $sql, $params);
    }

    public static function findById($id) {
      $db_conn = Database::getConnection();
      $sql = "select * from roomCategories where id = $1;";
      $params = array($id);
      $resp = pg_query_params($db_conn, $sql, $params);

      if ($resp && $row = pg_fetch_assoc($resp)) {
        return new RoomCategory($row);
      }

      return null;
    }

    public static function all() {
      $db_conn = Database::getConnection();
      $sql = "select * from roomCategories;";
      $resp = pg_query($db_conn, $sql);

      $roomCategories = array();
      while ($resp && $row = pg_fetch_assoc($resp)) {
        $roomCategories[] = new RoomCategory($row);
      }

      return $roomCategories;
    }

    public function delete() {
      $db_conn = Database::getConnection();
      $params = array($this->id);
      $sql = "delete from roomCategories where id = $1";
      $resp = pg_query_params($db_conn, $sql, $params);

      return $resp;
    }
} ?>
