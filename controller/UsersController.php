<?php

class UsersController extends Controller
{
  
  function login()
  {
    if($this->request->data)
    {
      $data = $this->request->data;
      $this->loadModel('User');
      $user = $this->User->findFirst(array(
        'conditionsor' => array(
          'login' => $data->login,
          'email' => $data->login,
        )
      ));
      if($user && password_verify($this->request->data->password, $user->password)){
        unset($user->password);
        $this->Session->write('User', $user);
        $this->Session->setFlash('Connexion Réussie !', 'success');
        $this->redirect('admin');
      }else{
        $this->Session->write('User', null);
        $this->Session->setFlash('Identifiant ou mot de passe incorrect', 'danger');
      }
      $this->request->data->password = '';
    }
    if($this->Session->isLogged()){
      if($this->Session->user('role') == 'admin'){
        $this->redirect('admin');
      }else {
        $this->redirect('');
      }
    }
  }

  function register($token = null)
  {
    if(!isset($token) || $token !== SecureConf::$RegisterToken){
      $this->e401("Accès non autorisé");
    }
    if($this->request->data)
    {
      $data = $this->request->data;
      $this->loadModel('User');
      $user = $this->User->findFirst(array(
        'conditionsor' => array(
          'login' => $data->login,
          'email' => $data->email,
        )
      ));
      if($user){
        $this->Session->setFlash('Identifiant déjà utilisé', 'danger');
      }else{
        $data->password = password_hash($data->password, PASSWORD_DEFAULT);
        $data->role = 'admin';
        $userId = $this->User->save($data);
        $data->id = $userId;
        
        $this->Session->write('User', $data);
        $this->Session->setFlash('Inscription Réussie !', 'success');
      }
      $this->redirect('admin');
    }else if($this->Session->isLogged()){
      if($this->Session->user('role') == 'admin'){
        $this->redirect('admin');
      }else {
        $this->redirect('');
      }
    }
  }

  function logout()
  {
    $this->Session->delete('User');
    $this->Session->setFlash('Vous êtes maintenant déconnecté', 'success');
    $this->redirect('users/login');
  }

}
?>