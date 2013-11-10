<?php class RoomPhoto extends Base {

    # http://www.php.net/manual/en/features.file-upload.post-method.php
    private $name;
    private $tmpName;
    private $type;
    private $size;
    private $error;

    private $path;
    private $roomId;

    public function __construct($data = array()) {
      parent::__construct($data);
      $this->path = APP_ROOT_FOLDER . "/app/assets/photos/";
    }

    public function setName($name) {
      $this->name = $name;
    }
    public function getName() {
      return $this->name;
    }

    public function setTmpName($tmpName) {
      $this->tmpName = $tmpName;
    }
    public function getTmpName() {
      return $this->tmpName;
    }

    public function setType($type) {
      $this->type = $type;
    }
    public function getType() {
      return $this->type;
    }

    public function setSize($size) {
      $this->size = $size;
    }
    public function getSize() {
      return $this->size;
    }

    public function setError($error) {
      $this->error = $error;
    }
    public function getError() {
      return $this->error;
    }

    public function setRoomId($roomId) {
      $this->roomId = $roomId;
    }
    public function getRoomId() {
      return $this->RoomId;
    }

    public function validates() {
      Validations::notEmpty($this->name, 'name', $this->errors);
      Validations::notEmpty($this->roomId, 'room_id', $this->errors);
      Validations::lessThen($this->size, pow(1024,3), 'size', $this->errors); // menor que 3 megabytes
      Validations::inclusionIn($this->type,
                               array('image/jpeg','image/jpg','image/png','image/gif'),
                               'type', $this->errors);
    }

    public function save() {
      if (!$this->isValid()) return false;

      $this->generateName();
      $sql = "INSERT INTO roomPhotos (name, size, type, room_id, created_at)
                                      values ($1, $2, $3, $4, $5) RETURNING ID;";

      $params = array($this->name, $this->size, $this->type, $this->roomId, $this->createdAt);
      $db_conn = Database::getConnection();
      $resp = pg_query_params($db_conn, $sql, $params);

      $row = pg_fetch_assoc($resp);
      $this->setId($row['id']);

      $this->saveToDisc();

      return true;
    }

    public static function findById($id) {
      $db_conn = Database::getConnection();
      $sql = "select * from roomPhotos where id = $1;";
      $params = array($id);
      $resp = pg_query_params($db_conn, $sql, $params);

      if ($resp && $row = pg_fetch_assoc($resp)) {
        return new RoomPhoto($row);
      }

      return null;
    }

    public function delete() {
      $db_conn = Database::getConnection();
      $params = array($this->id);
      $sql = "delete from roomPhotos where id = $1";
      $resp = pg_query_params($db_conn, $sql, $params);

      $this->deleteFromDisc();

      return $resp;
    }

    private function generateName() {
        // Recupera a extensão do arquivo
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $this->name, $ext);
        // Gera um nome único para a imagem
        if ($ext)
          $this->name = md5(uniqid(time())) . '.' . $ext[1];
        // md5: http://us1.php.net/manual/en/function.md5.php
        // uniqid: http://us1.php.net/manual/en/function.uniqid.php
        // time: unix timestamp in seconds
    }

    private function saveToDisc() {
      // move a imagem para para a pasta correta
      $original = $this->path . "original/" . $this->name;
      $thumb = $this->path . "thumb/" . $this->name;
      move_uploaded_file($this->tmpName, $original);

      require_once 'wide-image/WideImage.php';
      WideImage::load($original)->resize(125, 125, 'fill')->saveToFile($thumb);
    }

    private function deleteFromDisc() {
      $original = $this->path . "original/" . $this->name;
      $thumb = $this->path . "thumb/" . $this->name;
      unlink($original);
      unlink($thumb);
    }
} ?>
