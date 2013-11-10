<?php class Validations {

  public static function notEmpty($value, $key = null, &$errors = null){
    if (empty($value)){
      if ($key !== null && $errors !== null) {
        $msg = 'não deve ser vazio';
        $errors[$key] = $msg;
      }
      return false;
    }
    return true;
  }

  public static function validEmail($email, $key = null, &$errors = null){
    $pattern = '/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+/';

    if (preg_match($pattern, $email))
      return true;

    if ($key !== null && $errors !== null)
      $errors[$key] = 'não é válido';

    return false;
  }

  public static function isNumeric($value, $key = null, &$errors = null){
    if (is_numeric($value))
      return true;

    if ($key !== null && $errors !== null)
      $errors[$key] = 'deve ser um número';

    return false;
  }

  public static function uniqueField($value, $field, $table, &$errors = null) {
    $db_conn = Database::getConnection();
    $sql = "select {$field} from {$table} where lower({$field}) = $1";
    $params = array(strtolower($value));
    $resp = pg_query_params($db_conn, $sql, $params);

    if ($row = pg_fetch_assoc($resp)) {
      $errors[$field] = 'já existe um cadastro com esse dado';
      return false;
    }
    return true;
  }

  public static function uniqueFile($value, $key = null, &$errors = null) {
    if (file_exists($value)) {
      if ($key !== null && $errors !== null)
        $errors[$key] = 'já existe um arquivo com esse nome';
      return false;
    }
    return true;
  }

  public static function lessThen($value, $maxSize, $key = null, &$errors = null) {
    if ($value > $maxSize) {
      if ($key !== null && $errors !== null)
         $errors[$key] = 'Tamanho máximo exedido';
      return false;
    }
    return true;
  }

  public static function inclusionIn($value, $array, $key = null, &$errors = null) {
    if (in_array($value, $array)) {
      return true;
    }

    if ($key !== null && $errors !== null)
      $errors[$key] = 'Tipo não permitido';

    return false;
  }
} ?>
