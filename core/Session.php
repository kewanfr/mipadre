<?php
class Session{
  public function __construct(){
    if (!isset($_SESSION)) {
      session_start();
    }  
  }

  public function setFlash($message, $type = 'success', $retain = null, $list = null){
    if(!isset($_SESSION['flash'])){
      $_SESSION['flash'] = array();
    }
    $_SESSION['flash'][] = array(
      'message' => $message,
      'type' => $type,
      'retain' => $retain,
      'list' => $list
    );
  }

  public function addFlashMessage($message, $type = 'success', $retain = null, $list = null){
    if(!isset($_SESSION['flash'])){
      $_SESSION['flash'] = array();
    }
    $_SESSION['flash'][] = array(
      'message' => $message,
      'type' => $type,
      'retain' => $retain,
      'list' => $list
    );
  }

  public function flash(){
    if(isset($_SESSION['flash']) and is_array($_SESSION['flash']) and !empty($_SESSION['flash']) ){
      // die(debug($_SESSION['flash']));
      $html = '';
      foreach($_SESSION['flash'] as $key => $flash){
        $html .= '<div class="alert alert-'.$flash['type'].'">';
        $html .= $flash['message'].'<br>';
        if($flash['list'] and is_array($flash['list'])){
          $html .= '<ul>';
          foreach($flash['list'] as $k => $v){
            $html .= '<li>'.$v.'</li>';
          }
          $html .= '</ul>';
        }
        $html .= '</div>';
        if(!$flash['retain']){
          unset($_SESSION['flash'][$key]);
        }else {
          if($flash['retain'] == 'once'){
            unset($_SESSION['flash'][$key]['retain']);
          }else {
            $_SESSION['flash'][$key]['retain'] = $flash['retain'] - 1;
          }
        }
      }
      // $_SESSION['flash'] = array();
      return $html;
    }
  }

  public function write($key, $value){
    $_SESSION[$key] = $value;
  }

  public function read($key = null){
    if($key){
      return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    return $_SESSION;
  }

  public function delete($key){
    if(isset($_SESSION[$key])){
      unset($_SESSION[$key]);
    }
  }

  public function writeCookie($key, $value, $time = 3600){
    setcookie($key, $value, time() + $time, '/', "", false, true);
  }

  public function readCookie($key){
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
  }

  public function deleteCookie($key){
    setcookie($key, '', time() - 3600, '/', '', false, true);
  }

  public function isLogged(){
    $id = isset($_SESSION['User']->id);

    $file = ROOT.DS.'model'.DS.'User.php';
    require_once($file);
    $this->User = new User();

    if($id) return $id;

    if(isset($_COOKIE['uid']) && isset($_COOKIE['tk'])){
      $id = $_COOKIE['uid'];
      $token = $_COOKIE['tk'];
      $user = $this->User->findFirst(array(
        'conditions' => array(
          'id' => $id,
          'cookie_token' => $token,
        )
      ));
      if($user){
        $this->write('User', $user);
        return $user->id;
      }else {
        $this->deleteCookie('uid');
        $this->deleteCookie('tk');
        $this->delete('User');
      }
    }
    return false;
  }

  public function fetchAndUpdateSessionUser($id){

    $this->loadModel('User');

    $user = $this->User->findFirst(array(
      'conditions' => array(
        'id' => $id,
      )
    ));
    if($user){
      $this->write('User', $user);
      return $user->id;
    }else {
      $this->deleteCookie('uid');
      $this->deleteCookie('tk');
      $this->delete('User');
    }
  }

  public function updateSessionUser($user){
    if($user){
      $this->write('User', $user);
      return $user->id;
    }else {
      $this->deleteCookie('uid');
      $this->deleteCookie('tk');
      $this->delete('User');
    }
  }

  public function isLoggedAs($type = "user"){
    $id = $this->isLogged();
    switch ($type) {
      case 'admin':
        return isset($_SESSION['User']->id) && $_SESSION['User']->role == 'admin';
        break;
      case 'user':
        return isset($_SESSION['User']->id);
        break;
      case 'guest':
        return isset($_SESSION['Guest']->id);
        break;
      default:
        return false;
        break;
    }
  }

  public function user($key){
    if($this->isLogged()){
      return $_SESSION['User']->$key;
    }
    return false;
  }
}

?>