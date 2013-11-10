<?php namespace Admin;

class RoomsController  extends ApplicationController {

   public function index() {
      $rooms = \Room::all();
      $this->render(array('view' => 'admin/rooms/index.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'rooms' => $rooms));
   }

   public function show() {
     $room = \Room::findById($this->params[':id']);
     $this->render(array('view' => 'admin/rooms/show.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'photo' => new \RoomPhoto(),
                         'room' => $room));
   }

   public function _new() {
     $room  = new \Room();
     $this->render(array('view' => 'admin/rooms/new.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'room' => $room,
                         'submit' => 'Criar quarto',
                         'action' => $this->url_for('/admin/quartos'),
                         'categories' => \RoomCategory::all()
                         ));
   }

   public function create() {
     $room = new \Room($this->params['room']);
     if ($room->save()) {
       \Flash::message('success', 'Registro realizado com sucesso!');
       $this->redirect_to('/admin/quartos');
     }else{
       \Flash::message('danger', 'Existe dados incorretos no seu formulário!');
       $this->render(array('view' => 'admin/rooms/new.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'room' => $room,
                          'submit' => 'Criar quarto',
                          'action' => $this->url_for('/admin/quartos'),
                          'categories' => \RoomCategory::all()
                          ));
      }
   }

   public function edit() {
     $room = \Room::findById($this->params[':id']);
     $this->render(array('view' => 'admin/rooms/edit.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'room' => $room,
                         'categories' => \RoomCategory::all(),
                         'submit' => 'Salvar',
                         'action' => $this->url_for("/admin/quartos/{$room->getId()}")
                        ));
   }


   public function update() {
     $room = \Room::findById($this->params[':id']);

     if ($room->update($this->params['room'])) {
       \Flash::message('success', 'Registro atualizado com sucesso!');
       $this->redirect_to('/admin/quartos');
     }else{
       \Flash::message('danger', 'Existe dados incorretos no seu formulário!');
       $this->render(array('view' => 'admin/rooms/edit.phtml',
                          'layout' => 'admin/layout/application.phtml',
                          'room' => $room,
                          'categories' => \RoomCategory::all(),
                          'submit' => 'Salvar',
                          'action' => $this->url_for("/admin/quartos/{$room->getId()}")
                          ));
     }
   }

  public function destroy() {
    $room = \Room::findById($this->params[':id']);
    $room->delete();
    \Flash::message('success', 'Registro deletado com sucesso!');
    $this->redirect_to("/admin/quartos");
  }
} ?>
