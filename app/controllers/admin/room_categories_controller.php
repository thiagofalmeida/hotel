<?php namespace Admin;

class RoomCategoriesController  extends ApplicationController {

   public function index() {
      $room_categories = \RoomCategory::all();
      $this->render(array('view' => 'admin/room_categories/index.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'room_categories' => $room_categories));
   }

   public function _new() {
     $room_category  = new \RoomCategory();
     $this->render(array('view' => 'admin/room_categories/new.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'room_category' => $room_category,
                         'submit' => 'Nova categoria ',
                         'action' => $this->url_for('/admin/categorias-de-quarto')
                         ));
   }

   public function create() {
     $room_category = new \RoomCategory($this->params['room_category']);

     if ($room_category->save()) {
       \Flash::message('success', 'Registro realizado com sucesso!');
       $this->redirect_to('/admin/categorias-de-quarto');
     }else{
       \Flash::message('danger', 'Existe dados incorretos no seu formulário!');
       $this->render(array('view' => 'admin/room_categories/new.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'room_category' => $room_category,
                          'submit' => 'Novo categoria',
                          'action' => $this->url_for('/admin/categorias-de-quarto')
                          ));
      }
   }

   public function edit() {
     $room_category = \RoomCategory::findById($this->params[':id']);
     $this->render(array('view' => 'admin/room_categories/edit.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'room_category' => $room_category,
                         'submit' => 'Salvar',
                         'action' => $this->url_for("/admin/categorias-de-quarto/{$room_category->getId()}")
                        ));
   }


   public function update() {
     $room_category = \RoomCategory::findById($this->params[':id']);

     if ($room_category->update($this->params['room_category'])) {
       \Flash::message('success', 'Registro atualizado com sucesso!');
       $this->redirect_to('/admin/categorias-de-quarto');
     }else{
       \Flash::message('danger', 'Existe dados incorretos no seu formulário!');
       $this->render(array('view' => 'admin/room_categories/edit.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'room_category' => $room_category,
                          'submit' => 'Salvar',
                          'action' => $this->url_for("/admin/categorias-de-quarto/{$room_category->getId()}")
                          ));
     }
   }

  public function destroy() {
    $room_category = \RoomCategory::findById($this->params[':id']);
    $room_category->delete();
    \Flash::message('success', 'Registro deletado com sucesso!');
    $this->redirect_to("/admin/categorias-de-quarto");
  }
}
?>

