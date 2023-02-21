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
        $this->Session->setFlash('Connexion Réussie !', 'success', 1);
        $this->redirect('admin');
      }else{
        $this->Session->write('User', null);
        $this->Session->setFlash('Identifiant ou mot de passe incorrect', 'danger');
      }
      $this->request->data->password = '';
    }else if($this->Session->isLogged()){
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
      $errors = $this->Form->validation($this->request->data);
      if(!empty($errors)){
        $this->Session->setFlash("Veuillez corriger les erreurs suivantes :", "danger", null, $errors);
        $this->set('errors', $errors);
      }else{
        $data = $this->request->data;
        $this->loadModel('User');
        $user = $this->User->findFirst(array(
          'conditionsor' => array(
            'login' => $data->login,
            'email' => $data->email,
          )
        ));
        if($user){
          $this->Session->setFlash('Identifiant ou adresse email déjà utilisé', 'danger');
        }else{
          $password = password_hash($data->password, PASSWORD_DEFAULT);
          $role = 'admin';
          $newUser = (object) array(
            'login' => $data->login,
            'email' => $data->email,
            'password' => $password,
            'role' => $role,
          );
          $userId = $this->User->save($newUser);
          $newUser->id = $userId;
          unset($newUser->password);
          
          $this->Session->write('User', $newUser);
          $this->Session->setFlash('Inscription Réussie !', 'success', 1);
          $this->redirect('admin');
        }
      }
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