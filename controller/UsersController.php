<?php

class UsersController extends Controller
{

  function login()
  {
    if ($this->request->data) {
      $data = $this->request->data;
      $this->loadModel('User');
      $user = $this->User->findFirst(array(
        'conditionsor' => array(
          'login' => $data->login,
          'email' => $data->login,
        )
      ));
      if ($user && password_verify($this->request->data->password, $user->password)) {

        if (empty($user->cookie_token)) {
          $token = generateToken(Conf::$CookieTokenLength);
          $this->User->save((object)
          array(
            'id' => $user->id,
            'cookie_token' => $token
          ));
        } else {
          $token = $user->cookie_token;
        }
        $this->Session->writeCookie('tk', $token, Conf::$CookieDuration);
        $this->Session->writeCookie('uid', $user->id, Conf::$CookieDuration);
        unset($user->password);
        $this->Session->write('User', $user);
        $this->Session->setFlash('Connexion Réussie !', 'success', 1);
        $this->redirect('admin');
      } else {
        $this->Session->write('User', null);
        $this->Session->setFlash('Identifiant ou mot de passe incorrect', 'danger');
      }
      $this->request->data->password = '';
    } else if ($this->Session->isLogged()) {
      if ($this->Session->user('role') == 'admin') {
        $this->redirect('admin');
      } else {
        $this->redirect('');
      }
    }
  }

  function register($token = null)
  {
    // if (!isset($token) || $token !== SecureConf::$RegisterToken) {
    //   $this->e401("Accès non autorisé");
    // }
    if ($this->request->data) {
      $errors = $this->Form->validation($this->request->data);
      if (!empty($errors)) {
        $this->Session->setFlash("Veuillez corriger les erreurs suivantes :", "danger", null, $errors);
        $this->set('errors', $errors);
      } else {
        $data = $this->request->data;
        $this->loadModel('User');
        $user = $this->User->findFirst(array(
          'conditionsor' => array(
            'login' => $data->login,
            'email' => $data->email,
          )
        ));
        if ($user) {
          $this->Session->setFlash('Identifiant ou adresse email déjà utilisé', 'danger');
        } else {
          $password = password_hash($data->password, PASSWORD_DEFAULT);
          $token = generateToken(Conf::$CookieTokenLength);
          $role = 'user';
          $newUser = (object) array(
            'firstname' => $data->firstname,
            'lastname' => $data->lastname ? $data->lastname : null,
            'login' => $data->login,
            'email' => $data->email,
            'password' => $password,
            'role' => $role,
            'cookie_token' => $token
          );
          $userId = $this->User->save($newUser);
          $newUser->id = $userId;
          unset($newUser->password);

          $this->Session->writeCookie('tk', $token, Conf::$CookieDuration);
          $this->Session->writeCookie('uid', $userId, Conf::$CookieDuration);

          $this->Session->write('User', $newUser);
          $this->Session->setFlash('Inscription Réussie !', 'success');
          $this->redirect('users/new_account');
        }
      }
    } else if ($this->Session->isLogged()) {
      if ($this->Session->user('role') == 'admin') {
        $this->redirect('admin');
      } else {
        $this->redirect('users/profile');
      }
    }
  }

  function forgotpassword(){
    if ($this->request->data) {
      $data = $this->request->data;
      $this->loadModel('User');
      $user = $this->User->findFirst(array(
        'conditionsor' => array(
          'login' => $data->login,
          'email' => $data->login,
        )
      ));
      if ($user) {
        $token = generateToken(Conf::$CookieTokenLength);
        $this->User->save((object)
        array(
          'id' => $user->id,
          'reset_token' => $token
        ));

        $to = $user->email;
        $subject = "Mi Padre | Réinitialisation de mot de passe";
        // $message = "Bonjour,\n\nVous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :\n\nhttp://example.com/reset_password.php?token=xxxxxxxx\n\nCe lien sera valide pendant 24 heures. Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer ce message.\n\nCordialement,\nL'équipe de Example.com";
        $message = <<<TEXT
Bonjour,
Vous avez demandé la réinitialisation de votre mot de passe sur le site <?= Router::url('/') ?>. Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe : 
<a href='http://example.com/reset_password.php?token=xxxxxxxx' style='display:inline-block;padding:12px 24px;background-color:#007bff;color:#fff;text-decoration:none;border-radius:4px;'>Réinitialiser mon mot de passe</a>
Ce lien sera valide pendant 24 heures. Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer ce message.
<?= "test" ?>
TEXT;
        // Bonjour,\n\nVous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :\n\n<a href='http://example.com/reset_password.php?token=xxxxxxxx' style='display:inline-block;padding:12px 24px;background-color:#007bff;color:#fff;text-decoration:none;border-radius:4px;'>Réinitialiser mon mot de passe</a>\n\nCe lien sera valide pendant 24 heures. Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer ce message.\n\nCordialement,\nL'équipe de Example.com";

        $headers = "From: no-reply@mipadre.fr\r\n";
        $headers .= "Reply-To: no-reply@mipadre.fr\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";



        $result = mail($to, $subject, $message, $headers);
        debug($to);
        debug($message);
        debug($result);

        // $this->Session->setFlash('Un email vous a été envoyé pour réinitialiser votre mot de passe', 'success');
        // $this->redirect('users/login');
      } else {
        $this->Session->setFlash('Identifiant ou adresse email incorrect', 'danger');
      }
    }
  }

  function logout()
  {
    $this->Session->writeCookie('tk', '', -1);
    $this->Session->writeCookie('uid', '', -1);
    $this->Session->write('User', null);
    $this->Session->setFlash('Déconnexion Réussie !', 'success');
    $this->redirect('users/login');
  }

  function profile()
  {
    if (!$this->Session->isLogged()) {
      $this->redirect('users/login');
    }
    $this->loadModel('User');
    $user = $this->User->findFirst(array(
      'conditions' => array(
        'id' => $this->Session->user('id')
      )
    ));
    $this->Session->updateSessionUser($user);
    if ($this->request->data) {
      $data = $this->request->data;
      $errors = $this->Form->validation($data);
      if (!empty($errors)) {
        $this->Session->setFlash("Veuillez corriger les erreurs suivantes :", "danger", null, $errors);
        $this->set('errors', $errors);
      } else {
        $user->login = $data->login;
        $user->email = $data->email;
        if (!empty($data->password)) {
          $user->password = password_hash($data->password, PASSWORD_DEFAULT);
        }
        $this->User->save($user);
        $this->Session->setFlash('Profil mis à jour', 'success');
        $this->redirect('users/profile');
      }
    }
    $this->set('user', $user);
  }

  public function update($type)
  {
    $userId = $this->Session->user('id');

    if (!$this->Session->isLogged()) {
      $this->redirect('users/login');
    }
    $this->loadModel('User');
    $oldUser = $this->User->findFirst(array(
      'conditions' => array(
        'id' => $userId
      )
    ));
    if (!$oldUser) {
      $this->Session->setFlash('Utilisateur introuvable', 'danger');
      $this->redirect('users/profile');
    }
    if ($this->request->data) {

      switch ($type) {

        case 'lastname':
          $newLastname = $this->request->data->lastname;
          if(empty($newLastname) || $newLastname == $oldUser->lastname) {
            $this->Session->setFlash('Prénom inchangé', 'warning');
            return $this->redirect('users/profile#lastname');
          }
          $newUser = (object) array(
            'id' => $userId,
            'lastname' => $this->request->data->lastname,
          );
      
          $this->User->save($newUser);
          $this->Session->setFlash("Prénom mis à jour", 'success');
          break;
        case 'firstname':
          $newFirstname = $this->request->data->firstname;
          if(empty($newFirstname) || $newFirstname == $oldUser->firstname) {
            $this->Session->setFlash('Prénom inchangé', 'warning');
            return $this->redirect('users/profile#firstname');
          }
          $newUser = (object) array(
            'id' => $userId,
            'firstname' => $this->request->data->firstname,
          );
      
          $this->User->save($newUser);
          $this->Session->setFlash("Prénom mis à jour", 'success');
          break;

        case 'login':
          $newLogin = $this->request->data->login;
          if(empty($newLogin) || $newLogin == $oldUser->login) {
            $this->Session->setFlash('Nom d\'utilisateur inchangé', 'warning');
            return $this->redirect('users/profile#login');;
          }
          if ($this->User->findFirst(array(
            'conditions' => array(
              'login' => $newLogin
            )
          ))) {
            $this->Session->setFlash('Identifiant déjà utilisé', 'danger');
            return $this->redirect('users/profile#login');
          }
          $newUser = (object) array(
            'id' => $userId,
            'login' => $this->request->data->login,
          );
      
          $this->User->save($newUser);
          $this->Session->setFlash("Nom d'utilisateur mis à jour", 'success');
          break;

        case 'email':
          $newEmail = $this->request->data->email;
          if(empty($newEmail) || $newEmail == $oldUser->email) {
            $this->Session->setFlash('Adresse email inchangée', 'warning');
            return $this->redirect('users/profile#email');
          }
          if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->Session->setFlash('Adresse email invalide', 'danger');
            return $this->redirect('users/profile#email');
          }
          if ($this->User->findFirst(array(
            'conditions' => array(
              'email' => $newEmail
            )
          ))) {
            $this->Session->setFlash('Adresse email déjà utilisée', 'danger');
            return $this->redirect('users/profile#email');
          }
          $newUser = (object) array(
            'id' => $userId,
            'email' => $newEmail,
          );
      
          $this->User->save($newUser);
          $this->Session->setFlash('Adresse email mise à jour', 'success');
          break;

        case 'password':
          $newPassword = $this->request->data->new_password;
          $newPasswordConfirm = $this->request->data->confirm_new_password;

          if(empty($newPassword) || empty($newPasswordConfirm)) {
            $this->Session->setFlash('Veuillez remplir tous les champs', 'danger');
            return $this->redirect('users/profile#password');;
          }

          if($newPassword != $newPasswordConfirm) {
            $this->Session->setFlash('Les mots de passe ne correspondent pas', 'danger');
            return $this->redirect('users/profile#password');;
          }

          $newUser = (object) array(
            'id' => $userId,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
          );
      
          $this->User->save($newUser);
          $this->Session->setFlash('Mot de passe mis à jour', 'success');
          break;

        default:
          return $this->redirect('users/profile');;
      }
      $user = $this->User->findFirst(array(
        'fields' => 'id, login, email, role',
        'conditions' => array(
          'id' => $userId
        )
      ));
      $this->Session->write('User', $user);
    }
    $this->redirect('users/profile#'.$type);
  }

  function admin_list(){
    $this->loadModel('User');
    $users = $this->User->find(array(
      'fields' => 'id, firstname, lastname, login, email, role'
    ));
    $this->set('users', $users);
  }

  function admin_edit($id = null)
  {
    $this->loadModel('User');

    if (isset($id)) {
      $d['mode'] = "edit";
      $d['id'] = $id;
    } else {
      $d['mode'] = "add";
      $d['title'] = "Créer un utilisateur";
    }

    if ($this->request->data) {

      $d['title'] = "Modifier l'Utilisateur " . $this->request->data->firstname.' ('.$this->request->data->login.')';
      if($this->request->data->password){
        $this->request->data->password = password_hash($this->request->data->password, PASSWORD_DEFAULT);
      }
      if ($d['mode'] == "edit") {
        $this->request->data->id = $id;
        $this->User->save($this->request->data);
        $this->Session->setFlash("Informations modifiées avec succès !");
      } else {
        $newID = $this->User->save($this->request->data);
        $this->request->data->id = $newID;
        $this->Session->setFlash("Utilisateur créé avec succès !", "success", 2);
        $this->redirect('admin/users/edit/' . $newID);
      }
      $d['mode'] = "edit";
    } else if ($d['mode'] == "edit") {
      $this->request->data = $this->User->getUser($id);
      if (empty($this->request->data)) {
        $this->e404('Cet utilisateur n\'existe pas');
      }
      $d['title'] = "Modifier l'Utilisateur " . $this->request->data->firstname.' ('.$this->request->data->login.')';
    }
    unset($this->request->data->password);

    $this->set($d);
  }

  

  public function admin_activate($id){
    $this->loadModel('User');
    $user = $this->User->findFirst(array(
      'conditions' => array(
        'id' => $id
      )
    ));
    if(!$user){
      $this->Session->setFlash('Utilisateur introuvable', 'danger');
      $this->redirect('admin/users/list');
    }
    $user->role = "admin";
    $this->User->save($user);
    $this->Session->setFlash('Utilisateur activé', 'success');
    $this->redirect('admin/users/list');
  }


  function admin_delete($id)
  {

    $this->loadModel('User');
    $user = $this->User->getUser($id, "id, firstname, login");
    if (empty($user)) {
      $this->e404('Cette page n\'existe pas');
    }

    $this->User->delete($id);
    $this->Session->addFlashMessage("Utilisateur ".$user->firstname . " (" . $user->login . ") Supprimé avec succès !");
    $this->redirect('admin/users/list');
  }

  function new_account(){
    if($this->Session->isLogged()){
      if($this->Session->isLoggedAs("admin")) $this->redirect('admin/');
      else $this->redirect('users/profile');
    }

  }

}
