<?php
abstract class Base {
    protected $id;
    protected $createdAt;
    protected $errors = array();

    public function __construct($data = array()){
      $this->createdAt = date('Y-m-d H:i:s');
      $this->setData($data);
    }

    public function validates(){}

    public function getId() {
      return $this->id;
    }

    public function setId($id) {
      $this->id = $id;
    }

    public function getCreatedAt(){
      return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
      $this->createdAt = $createdAt;
    }

    public function errors($index = null) {
      if ($index == null)
        return $this->errors;

      if (isset($this->errors[$index]))
        return $this->errors[$index];

      return false;
    }

    public function isValid() {
      $this->errors = array();
      $this->validates();
      return empty($this->errors);
    }

    public function newRecord(){
      return empty($this->id);
    }

    public function changedFieldValue($field, $table) {
      $db_conn = Database::getConnection();
      $sql = "select {$field} from {$table} where id = $1";
      $resp = pg_query_params($db_conn, $sql, array($this->id));

      $method = "get".$field;
      $result = pg_fetch_assoc($resp);
      $email_db = $result[$field];
      Logger::getInstance()->log("Mudou: {$this->$method()}", Logger::NOTICE);
      pg_close($db_conn);
      return $email_db !== $this->$method();
    }

    private function snakToCamelCase($value) {
      return preg_replace('/_/', '', $value);
    }

    public function setData($data = array()) {
      foreach($data as $key => $value){
         $method = "set{$key}";
         $method = $this->snakToCamelCase($method);
         $this->$method(strip_tags(trim($value)));
      }
    }
} ?>
