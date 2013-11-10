<?php class UserSession extends Base {
    private $email;
    private $password;

    public function setEmail($email) {
      $this->email = $email;
    }

    public function setPassword($password) {
      $this->password = $password;
    }

    public function getEmail() {
      return $this->email;
    }

    public function wasCreate() {
      $db_conn = Database::getConnection();
      $sql = "select id from users where email = $1 and password = $2";
      $params = array($this->email, sha1($this->password));
      $resp = pg_query_params($db_conn, $sql, $params);

      if ($resp && $user = pg_fetch_assoc($resp)) {
        $_SESSION['user']['id'] = $user['id'];
        Logger::getInstance()->log("Usuário: {$user['id']} logou no sistema" , Logger::NOTICE);
        return true;
      }
      Logger::getInstance()->log("Login:" . print_r(error_get_last(), true), Logger::ERROR);
      return false;
    }

    public function destroy(){
      unset($_SESSION['user']);
    }
} ?>
