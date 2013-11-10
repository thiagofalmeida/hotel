<?php
class Contact extends Base {
    private $name;
    private $email;
    private $content;

    public function setName($name) {
      $this->name = $name;
    }

    public function getName() {
      return $this->name;
    }

    public function setContent($content) {
      $this->content = $content;
    }

    public function getContent() {
      return $this->content;
    }

    public function setEmail($email) {
      $this->email = $email;
    }

    public function getEmail() {
      return $this->email;
    }

    public function validates() {
      Validations::notEmpty($this->name, 'name', $this->errors);
      Validations::validEmail($this->email, 'email', $this->errors);
      Validations::notEmpty($this->content, 'content', $this->errors);
    }

    public function save() {
      Database::getConnection();
      print "Conexão e Seleção OK!"; 
      mysql_query("insert into contacts (name, email, content) values ('$this->name', '$this->email', '$this->content')");
      /* if (!$this->isvalid()) return false;

      $sql = "insert into contacts (name, email, content, created_at) values ('d', 'd@gj.c', '1231231')";

      
      $db = mysql_connect("localhost:3307", "root", "novasenha");
      $db2 = mysql_select_db("teste", $db);
      mysql_query($sql);

      if (!$resp) {
        Logger::getInstance()->log("Falha para salvar o contato: " . print_r($this, TRUE), Logger::ERROR);
        Logger::getInstance()->log("Error: " . print_r(error_get_last(), true), Logger::ERROR);
        return false;
      }
      mysql_close($conecta); */
      return true;
    }

    public static function all() {
      $db_conn = Database::getConnection();
      $sql = "select * from contacts;";
      $resp = pg_query($db_conn, $sql);

      $contacts = array();
      while ($resp && $row = pg_fetch_assoc($resp)) {
        $contacts[] = new Contact($row);
      }

      return $contacts;
    }
} ?>
