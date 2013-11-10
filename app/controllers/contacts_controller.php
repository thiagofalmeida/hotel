<?php
class ContactsController  extends ApplicationController {

  public function _new() {
    $contact = new Contact();
    $this->render(array('view' => 'contacts/new.phtml','contact' => $contact));
  }

  public function create() {
    # $_POST['contact'] retorna os mesmos dados que $this->params['contacts']
    $contact = new Contact($this->params['contact']);

    if ($contact->save()) {
      Flash::message('success', 'Mensagem enviada com sucesso!');
      $this->redirect_to('/');
    }else{
      Flash::message('danger', 'Existe dados incorretos no seu formulário!');
      $this->render(array('view' => 'contacts/new.phtml',
                          'contact' => $contact));
    }
  }
}
?>
