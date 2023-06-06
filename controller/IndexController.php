<?php

class IndexController extends Controller
{

  function index()
  {
    // If logged as guest redirect to guest page
    if($this->Session->isLoggedAs('guest')){
      return $this->redirect('client/edit');
    }
    if($this->Session->isLoggedAs('admin')){
      return $this->redirect('admin');
    }
    if($this->Session->isLoggedAs('user')){
      return $this->e403("Vous n'êtes pas autorisé à accéder à cette page");
    }
    return $this->redirect('users/login');
  }

  function changelog()
  {
    $this->set('title_for_layout', 'Changelog');
  }
}
?>