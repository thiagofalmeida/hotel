<?php class SessionsController  extends ApplicationController {

  public function _new(){
    $this->render(array('view' => 'sessions/new.phtml',
                        'user' => new UserSession()));
  }

  public function create(){
    $session = new UserSession($this->params['user']);

    if ($session->wasCreate()) {
      Flash::message('success', 'Login realizado com sucesso!');
      $this->redirect_to('/');
    }else{
      Flash::message('danger', 'Login/senha incorretas!');
      $this->render(array('view' => 'sessions/new.phtml',
                          'user' => $session));
    }
  }

  public function destroy() {
    $session = new UserSession();
    $session->destroy();
    $this->redirect_to('/login');
  }
} ?>
