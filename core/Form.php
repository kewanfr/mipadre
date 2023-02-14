<?php 
class Form{

  public $controller;

  public function __construct($controller){
    $this->controller = $controller;
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
    }else{
      $html .= '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" class="form-control"'.$attr.'>';
    }
    // $html .= '</div>';
    return $html;
  }

}

?>