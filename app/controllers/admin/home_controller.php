<?php namespace Admin;

class HomeController extends ApplicationController {
  
  public function index(){
    $this->render(array('view' => 'admin/home/index.phtml',
                        'layout' => 'admin/layout/application.phtml'));
  }

} ?>
