<?php class Room extends Base {
    private $name;
    private $price;
    private $category;
    private $categoryId;

    public function setName($name) {
      $this->name = $name;
    }
    public function getName() {
      return $this->name;
    }

    public function setPrice($price) {
      $this->price = $price;
    }
    public function getPrice() {
      return $this->price;
    }

    public function setCategoryId($categoryId) {
      $this->categoryId = $categoryId;
    }
    public function getCategoryId() {
      return $this->categoryId;
    }

    public function setCategory($category) {
      $this->category = $category;
      $this->setCategoryId($category->getId());
    }
    public function getCategory() {
      return $this->category;
    }

    public function photos() {
      $db_conn = Database::getConnection();
      $sql = "select * from roomPhotos where room_id = $1;";
      $params = array($this->id);
      $resp = pg_query_params($db_conn, $sql, $params);

      $photos = array();
      while ($resp && $row = pg_fetch_assoc($resp)) {
        $photos[] = new RoomPhoto($row);
      }

      return $photos;
    }

    public function validates() {
      Validations::notEmpty($this->name, 'name', $this->errors);
      Validations::notEmpty($this->categoryId, 'category_id', $this->errors);
      Validations::isNumeric($this->price, 'price', $this->errors);
    }

    public function save() {
      if (!$this->isvalid()) return false;

      $sql = "INSERT INTO rooms (name, price, category_id, created_at) values ($1, $2, $3, $4) RETURNING ID;";
      $params = array($this->name, $this->price, $this->categoryId, $this->createdAt);
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
      $params = array($this->name, $this->price, $this->categoryId, $this->id);
      $sql = "update rooms set name=$1, price=$2, category_id=$3 where id = $4";

      return pg_query_params($db_conn, $sql, $params);
    }

    public static function findById($id) {
      $db_conn = Database::getConnection();
      $sql = "SELECT
                r.id as room_id, r.name as room_name, r.price as room_price,
                r.created_at as room_created_at,
                c.id as category_id, c.name as category_name,
                c.created_at as category_created_at
              FROM
                rooms r
              INNER JOIN
                roomCategories c
              ON
                r.category_id = c.id and
                r.id = $1;";

      $params = array($id);
      $resp = pg_query_params($db_conn, $sql, $params);

      if ($resp && $row = pg_fetch_assoc($resp)) {
        return Room::createFromDb($row);
      }

      return null;
    }

    public static function all() {
      $db_conn = Database::getConnection();
      $sql = "SELECT
                r.id as room_id, r.name as room_name, r.price as room_price,
                r.created_at as room_created_at,
                c.id as category_id, c.name as category_name,
                c.created_at as category_created_at
              FROM
                rooms r
              INNER JOIN
                roomCategories c
              ON
                (r.category_id = c.id);";

      $resp = pg_query($db_conn, $sql);

      $rooms = array();
      while ($resp && $row = pg_fetch_assoc($resp)) {
        $rooms[] = Room::createFromDb($row);
      }

      return $rooms;
    }

    public function delete() {
      $db_conn = Database::getConnection();
      $params = array($this->id);
      $sql = "delete from rooms where id = $1";
      $resp = pg_query_params($db_conn, $sql, $params);

      return $resp;
    }

    private static function createFromDb($row) {
      $category = new RoomCategory();
      $category->setId($row['category_id']);
      $category->setName($row['category_name']);

      $room = new Room();
      $room->setId($row['room_id']);
      $room->setName($row['room_name']);
      $room->setPrice($row['room_price']);
      $room->setCreatedAt($row['room_created_at']);
      $room->setCategory($category);

      return $room;
    }
} ?>
