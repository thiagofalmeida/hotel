<?php
  class Database {

    public static function getConnection() {
      require 'database_config.php';
      $db_con = mysql_connect($db_server, $db_user, $db_pswd) or die('Não foi possivel conectar com o banco de dados');
      mysql_select_db($db_name, $db_con);
      /*
      if ($db_con) {
        Logger::getInstance()->log("Aberta conexão com banco de dados!", Logger::NOTICE);
        return $db_con;
      } else {
        Logger::getInstance()->log("Falha na conexão com banco de dados!", Logger::FATAL);
        die("Falha na conexão com o banco de dados");
      }
      $name = "asdaasdasdas";
      $em = "a@aasasa.com";
      $con = "lolking";
      $conecta = mysql_connect($db_server, $db_user, $db_pswd) or print (mysql_error());
      mysql_select_db($db_name, $conecta) or print(mysql_error()); 
      print "Conexão e Seleção OK!"; 
      mysql_query("insert into contacts (name, email, content) values ('$name', '$em', '$con')");
      mysql_close($conecta); 
      header('location: /fale-conosco');*/
    }
  }
?>