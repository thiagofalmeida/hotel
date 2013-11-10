<?php class HomeController extends ApplicationController {

   public function index() {
      $title = 'Padrão MVC';
      $this->render(array('view' => 'home/index.phtml', 'title' => $title));
   }

} ?>
