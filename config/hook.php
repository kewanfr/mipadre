<?php

if($this->request->prefix == "admin"){
  $this->layout = "admin";
  if(!$this->Session->isLogged() || $this->Session->user('role') != 'admin'){
    $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');

    return $this->redirect('users/login');
  }
}

?>