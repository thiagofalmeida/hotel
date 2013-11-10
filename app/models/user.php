<?php class User extends Base {
    private $name;
    private $email;
    private $password;

    public function setName($name) {
      $this->name = $name;
    }
    public function getName() {
      return $this->name;
    }

    public function setEmail($email) {
      $this->email = $email;
    }
    public function getEmail() {
      return $this->email;
    }

    public function setPassword($password) {
      $this->password= $password;
    }

    public function validates() {
      Validations::notEmpty($this->name, 'name', $this->errors);

      /* Como o campo é único é necessário atualizar caso não tenha mudado*/
      if ($this->newRecord() || $this->changedFieldValue('email', 'users')) {
        Validations::validEmail($this->email, 'email', $this->errors);
        Validations::uniqueField($this->email, 'email', 'users', $this->errors);
      }

      if ($this->newRecord()) /* Caso a senha seja vazia não deve ser atualizada */
        Validations::notEmpty($this->password, 'password', $this->errors);
    }

    public function save() {
      if (!$this->isvalid()) return false;

      $sql = "INSERT INTO users (name, email, password, created_at) values ($1, $2, $3, $4) RETURNING ID;";
      # sha1 criptografa a senha, porém é mais recomendado a utilização de um Blowfish, pesquise sobre!!
      $params = array($this->name, $this->email, sha1($this->password), $this->createdAt);
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
      $params = array($this->name, $this->email, $this->id);

      if (empty($this->password)) {
        $sql = "update users set name=$1, email=$2 where id = $3";
      } else {
        $params[] = sha1($this->password);
        $sql = "update users set name=$1, email=$2, password=$4 where id = $3";
      }

      return pg_query_params($db_conn, $sql, $params);
    }

    public static function findById($id) {
      $db_conn = Database::getConnection();
      $sql = "select * from users where id = $1;";
      $params = array($id);
      $resp = pg_query_params($db_conn, $sql, $params);

      if ($resp && $row = pg_fetch_assoc($resp)) {
        $user = new User($row);
        return $user;
      }

      return null;
    }

    public static function all() {
      $db_conn = Database::getConnection();
      $sql = "select * from users;";
      $resp = pg_query($db_conn, $sql);

      $users = array();
      while ($resp && $row = pg_fetch_assoc($resp)) {
        $users[] = new User($row);
      }

      return $users;
    }

    public function delete() {
      $db_conn = Database::getConnection();
      $params = array($this->id);
      $sql = "delete from users where id = $1";
      $resp = pg_query_params($db_conn, $sql, $params);

      return $resp;
    }
} ?>

