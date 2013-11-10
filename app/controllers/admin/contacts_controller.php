<?php namespace Admin;

class ContactsController  extends ApplicationController {

  public function index(){
     $contacts = \Contact::all();
     $this->render(array('view' => 'admin/contacts/index.phtml',
                         'layout' => 'admin/layout/application.phtml',
                         'contacts' => $contacts));
  }

} ?>
