<?php class UsersController  extends ApplicationController {

  protected $beforeAction = array('authenticated' => array('edit', 'update'));

  public function _new() {
    $this->render(array('view' => 'users/new.phtml',
                        'action' => $this->url_for('/registre-se'),
                        'submit' => 'Cadastre-se',
                        'user' => new User()));
  }

  public function create(){
    $user = new User($this->params['user']);

    if ($user->save()) {
      Flash::message('success', 'Registro realizado com sucesso!');
      $this->redirect_to('/login');
    }else{
      Flash::message('danger', 'Existe dados incorretos no seu formulário!');
      $this->render(array('view' => 'users/new.phtml',
                          'action' => $this->url_for('/registre-se'),
                          'submit' => 'Cadastre-se',
                          'user' => $user));
    }
  }

  public function edit() {
    $this->render(array('view' => 'users/edit.phtml',
                        'action' => $this->url_for('/perfil'),
                        'submit' => 'Atualizar',
                        'user' => $this->currentUser()));
  }

  public function update(){
    $user = $this->currentUser();

    if ($user->update($this->params['user'])) {
      Flash::message('success', 'Registro atualizado com sucesso!');
      $this->redirect_to('/');
    }else{
      Flash::message('danger', 'Existe dados incorretos no seu formulário!');
      $this->render(array('view' => 'users/new.phtml',
                          'action' => $this->url_for('/perfil'),
                          'submit' => 'Atualizar',
                          'user' => $user));
    }
  }
} ?>
