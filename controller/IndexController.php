<?php

class IndexController extends Controller
{

  function index()
  {
    // If logged as guest redirect to guest page
    if($this->Session->isLoggedAs('guest')){
      return $this->redirect('client/show');
    }
    if($this->Session->isLoggedAs('admin')){
      return $this->redirect('admin');
    }
    return $this->redirect('users/login');
  }
}
?>