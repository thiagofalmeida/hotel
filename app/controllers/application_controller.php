<?php class ApplicationController extends BaseController {
  private $currentUser;

  public function currentUser() {
    if ($this->currentUser === null && isset($_SESSION['user']['id'])) {
      $this->currentUser = User::findById($_SESSION['user']['id']);
    }
    return $this->currentUser;
  }

  public function authenticated() {
    if ($this->currentUser() === null) {
      Flash::message('warning', 'Você deve estar logado para acessar esta página');
      $this->redirect_to('/login');
    }
  }
}
