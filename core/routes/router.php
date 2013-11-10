<?php class Router {

  private $url;
  private $post_routes = array();
  private $get_routes = array();

  public function __construct($url) {
    Logger::getInstance()->log($url, Logger::NOTICE);
    $this->url = str_replace(SITE_ROOT, '', $url);
  }

  public function get($route, $options){
    $this->get_routes[$route] = $options;
  }

  public function post($route, $options){
    $this->post_routes[$route] = $options;
  }

  public function load() {
     if ($_SERVER['REQUEST_METHOD'] === 'POST')
        $this->find($this->post_routes);
     else
        $this->find($this->get_routes);
  }

  private function find($routes) {
      foreach($routes as $route => $options){
        $params = array();

        if ($this->isRightRoute($route, $params)) {
           Logger::getInstance()->log("match: {$route} with {$this->url}", Logger::NOTICE);
           $controller_name = $options['controller'];
           $action_name = $options['action'];

           $merged_params = array_merge($this->params(), $params);

           $controller = new $controller_name();

           $controller->beforeAction($action_name);
           $controller->setParams($merged_params);
           $controller->$action_name();
           return;
        }
     }
     $this->page_not_found();
  }

  private function isRightRoute($route, &$params) {
    $route = explode('/', $route);
    $url = explode('/', $this->url);

    if (sizeof($route) != sizeof($url)) return false;

    for ($i = 0; $i < sizeof($route); $i++) {
      if (preg_match('/^:[a-z,_]+$/', $route[$i])) {
          $params[$route[$i]] = $url[$i];
          continue;
      }else if ($route[$i] !== $url[$i]) {
        return false;
      }
    }

    return true;
  }

  private function params() {
     if ($_SERVER['REQUEST_METHOD'] === 'POST')
       return $_POST;
     else
       return $_GET;
  }

  private function page_not_found() {
     Logger::getInstance()->log("URL NOT FOUND: " . $_SERVER['REQUEST_URI'], Logger::ERROR);
     Flash::message('danger', 'Página não encontrada!');
     header('location: ' . SITE_ROOT . '/');
     exit();
  }
}?>
