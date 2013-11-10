<?php
  /* Inclui arquivos css
   * Se o caminho começar com / deve ser considerado a partir da pasta ASSETS_FOLDER
   * caso contrário a partir de ASSETS_FORLDER/css/
   */
  function stylesheet_include_tag() {
     $params = func_get_args();

     foreach($params as $param) {
        $path = ASSETS_FOLDER;
        $path .= (substr($param, 0, 1) === '/') ? $param : '/css/' . $param ;
        echo "<link href='{$path}' rel='stylesheet' type='text/css' />";
     }
  }

  /*
   * Inclui arquivos js
   * Se o caminho começar com / deve ser considerado a partir da pasta ASSETS_FOLDER
   * caso contrário a partir de ASSETS_FORLDER/css/
   */
  function javascript_include_tag(){
    $params = func_get_args();
    foreach($params as $param){
      $path = ASSETS_FOLDER;
      $path .= (substr($param, 0, 1) === '/') ? $param : '/js/' . $param ;
      echo "<script src='{$path}' type='text/JavaScript'></script>";
    }
  }

  /*
   * Função para criar links.
   * Importante para definir os caminhos dos arquivos
   * Caso começe com / indica caminho absolute a partir do root da aplicação,
   * caso contrário é camaminho relativo
   */
  function link_to($path, $name, $options = '') {
     if (substr($path, 0, 1) == '/')
        $link = SITE_ROOT . $path;
     else
        $link = $path;
     return "<a href='{$link}' {$options}> $name </a>";
  }

  /*
   * Função para converter boleano em formato amigável
  */
  function pretty_bool($value){
    return $value ? 'Sim' : 'Não';
  }

  function format_date($date){
    return date('d/m/Y h:m:s', strtotime($date));
  }

  function activeClass($route) {
    $route = SITE_ROOT . $route;
    if (preg_match('#^' . $route . '$#', $_SERVER['REQUEST_URI']))
      return 'active';

    return '';
  }

  function image_tag($path, $name, $format, $options = "") {
    $path = ASSETS_FOLDER . '/' . $path . '/' . $format . '/' . $name ;
    return "<img src=\"{$path}\" {$options} />";
  }
?>
