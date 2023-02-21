<?php 
class Form{

  public $controller;

  public function __construct($controller){
    $this->controller = $controller;
  }

  public function notEmpty($data){
    return !empty($data);
  }

  public function validation($data){
    $validators = array(
      "login" => array(
        "rule" => function ($data) {
          return !empty($data->login);
        },
        "message" => "Le nom d'utilisateur est vide.",
      ),
      "email" => array(
        "rule" => function ($data) {
          $emailRegex = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$/';
          return !empty($data->email) && preg_match($emailRegex, $data->email);
        },
        "message" => "L'adresse email n'est pas valide.",
      ),
      "password" => array(
        "rule" => function ($data) {
          $passwordRegex = '/^^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,30}$/';
          return !empty($data->password) && preg_match($passwordRegex, $data->password);
        },
        "message" => "Le mot de passe doit comporter au moins 5 caractères, une majuscule et un chiffre.",
      ),
      "passwordConfirm" => array(
        "rule" => function ($data) {
          return !empty($data->passwordConfirm) && $data->passwordConfirm == $data->password;
        },
        "message" => "Les mots de passe ne correspondent pas.",
      ),
    );

    $validateErrors = array();
    foreach($data as $k => $v){
      if(isset($validators[$k])){
        if(!$validators[$k]["rule"]($data)){
          $validateErrors[$k] = $validators[$k]["message"];
        }
      }
    }
    return $validateErrors;
    
  }

  public function input($name, $label, $options = array()){
    $type = isset($options['type']) ? $options['type'] : 'text';
    if(isset($this->controller->request->data->$name)){
      $value = $this->controller->request->data->$name;
    }else{
      $value = '';
      if(isset($options['defaultVal'])){
        $value = $options['defaultVal'];
      }
      unset($options['defaultVal']);
    }
    if($label == "hidden"){
      return '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
    }
    $invalidFeedback = isset($options["invalidFeedback"]) ? '<div class="invalid-feedback" id="invalid-'.$name.'">'.$options["invalidFeedback"].'</div>' : '';
    unset($options["invalidFeedback"]);
    // $html = '<div class="'.$divClass.'">';
    $html = '';
    $html .= '<label for="'.$name.'">'.$label.'</label>';
    $attr = '';
    foreach($options as $k => $v){
      if($k != 'type'){
        $attr .= $k.'="'.$v.'"';
      }
    }
    if($type == 'textarea'){
      $html .= '<textarea name="'.$name.'" id="'.$name.'" class="form-control"'.$attr.'>'.$value.'</textarea>';
      $html .= $invalidFeedback;
    }else if ($type == 'passwordWithBtn') {
      $html .= '<div class="input-group">
        <input type="password" name="'.$name.'" id="'.$name.'" value="'.$value.'" class="form-control"'.$attr.' autocomplete="on">';

      $html .= '
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" id="show-'.$name.'">
            <i class="fa fa-eye-slash"></i>
          </button>
        </div>';
      $html .= $invalidFeedback;
      $html .= '</div>';
    }else{
      $html .= '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" class="form-control"'.$attr.'>';
      $html .= $invalidFeedback;
    }
    // $html .= '</div>';
    return $html;
  }

}
