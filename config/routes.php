<?php
    require 'application.php';
    $router = new Router($_SERVER['REQUEST_URI']);

    $router->get('/', array('controller' => 'HomeController', 'action' => 'index'));

    /* Rotas para os contatos
    ------------------------- */
    $router->get('/fale-conosco', array('controller' => 'ContactsController', 'action' => '_new'));
    $router->post('/fale-conosco', array('controller' => 'ContactsController', 'action' => 'create'));
    /* Fim das rotas para os contatos
    --------------------------------- */

    $router->load();
?>
