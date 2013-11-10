<?php namespace Admin;

class RoomPhotosController  extends ApplicationController {

   public function create() {
     $photo = new \RoomPhoto($_FILES['photo']);
     $photo->setRoomId($this->params[':id']);

     if ($photo->save()) {
        \Flash::message('success', 'Photo adicionada com sucesso!');
        $this->redirect_to("/admin/quartos/{$this->params[':id']}");
     }else{
       \Flash::message('danger', 'Dados incorretos no upload!');
       $room = \Room::findById($this->params[':id']);
       $this->render(array('view' => 'admin/rooms/show.phtml',
                           'layout' => 'admin/layout/application.phtml',
                           'photo' => $photo,
                           'room' => $room));
      }
   }

  public function destroy() {
    $photo = \RoomPhoto::findById($this->params[':id']);
    $photo->delete();
    \Flash::message('success', 'Imagem removida com sucesso!');
    $this->redirect_to($this->back());
  }
} ?>
