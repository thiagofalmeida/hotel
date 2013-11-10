<?php namespace Admin;

class SessionsController  extends ApplicationController {

  protected $beforeAction = array();

  public function _new(){
    $this->render(array('view' => 'admin/sessions/new.phtml',
                        'layout' => 'admin/layout/application.phtml',
                        'user' => new AdminSession()));
  }

  public function create(){
    $session = new AdminSession($this->params['user']);

    if ($session->wasCreate()) {
      \Flash::message('success', 'Login realizado com sucesso!');
      $this->redirect_to('/admin');
    }else{
      \Flash::message('danger', 'Login/senha incorretas!');
      $this->render(array('view' => 'admin/sessions/new.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'user' => $session));
    }
  }

  public function destroy() {
    $session = new AdminSession();
    $session->destroy();
    $this->redirect_to('/admin');
  }
} ?>
