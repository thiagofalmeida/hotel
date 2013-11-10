<?php namespace Admin;

class UsersController  extends ApplicationController {

   public function index() {
      $users = \User::all();
      $this->render(array('view' => 'admin/users/index.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'users' => $users));
   }

   public function show() {
     $user = \User::findById($this->params[':id']);
     $this->render(array('view' => 'admin/users/show.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'user' => $user));
   }


   public function _new() {
     $user = new \User();
     $this->render(array('view' => 'admin/users/new.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'user' => $user, 
                         'submit' => 'Novo usuário',
                         'action' => $this->url_for('/admin/usuarios')
                         ));
   }

   public function create() {
    $user = new \User($this->params['user']);

    if ($user->save()) {
      \Flash::message('success', 'Registro realizado com sucesso!');
      $this->redirect_to('/admin/usuarios');
    }else{
      \Flash::message('danger', 'Existe dados incorretos no seu formulário!');
      $this->render(array('view' => 'admin/users/new.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'user' => $user,
                          'submit' => 'Novo usuário',
                          'action' => $this->url_for('/admin/usuarios')
                          ));
     }
   }

   public function edit() {
     $user = \User::findById($this->params[':id']);
     $this->render(array('view' => 'admin/users/edit.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'user' => $user,
                         'submit' => 'Salvar',
                         'action' => $this->url_for("/admin/usuarios/{$user->getId()}")
                        ));
   }


   public function update() {
     $user = \User::findById($this->params[':id']);

     if ($user->update($this->params['user'])) {
       \Flash::message('success', 'Registro atualizado com sucesso!');
       $this->redirect_to('/admin/usuarios');
     }else{
       \Flash::message('danger', 'Existe dados incorretos no seu formulário!');
       $this->render(array('view' => 'admin/users/edit.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'user' => $user,
                          'submit' => 'Salvar',
                          'action' => $this->url_for("/admin/usuarios/{$user->getId()}")
                          ));
     }
   }

  public function destroy() {
    $user = \User::findById($this->params[':id']);
    $user->delete();
    \Flash::message('success', 'Usuário deletado com sucesso!');
    $this->redirect_to("/admin/usuarios");
  }
}
?>
