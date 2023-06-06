<?php

if($this->request->prefix == "admin"){
  $this->layout = "admin";
  if($this->Session->user('role') != 'admin'){
    return $this->e403("Vous n'êtes pas autorisé à accéder à cette page");
  }
  if(!$this->Session->isLogged()){
    $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');

    return $this->redirect('users/login');
  }
}

?>