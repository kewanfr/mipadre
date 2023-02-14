<?php
class Session{
  public function __construct(){
    if (!isset($_SESSION)) {
      session_start();
    }  
  }

  public function setFlash($message, $type = 'success', $retain = null){
    if(!isset($_SESSION['flash'])){
      $_SESSION['flash'] = array();
    }
    $_SESSION['flash'][] = array(
      'message' => $message,
      'type' => $type,
      'retain' => $retain
    );
  }

  public function addFlashMessage($message, $type = 'success', $retain = null){
    if(!isset($_SESSION['flash'])){
      $_SESSION['flash'] = array();
    }
    $_SESSION['flash'][] = array(
      'message' => $message,
      'type' => $type,
      'retain' => $retain
    );
  }

  public function flash(){
    if(isset($_SESSION['flash']) and is_array($_SESSION['flash']) and !empty($_SESSION['flash']) ){
      $html = '';
      foreach($_SESSION['flash'] as $key => $flash){
        $html .= '<div class="alert alert-'.$flash['type'].'">';
        $html .= $flash['message'].'<br>';
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

  public function isLogged(){
    return isset($_SESSION['User']->id);
  }

  public function isLoggedAs($type = "user"){
    debug($_SESSION);
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