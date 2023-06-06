<?php
class User extends Model{
    
    //public $table = 'posts';
    
  public function getUser($id, $fields = ['*'])
  {
    return $this->findFirst(array(
      'fields' => $fields,
      'conditions' => array(
        'id' => $id
      )
    ));
  }

  public function getUsers($fields)
  {
    return $this->find(array(
      'fields' => $fields
    ));
  }

  public function getLastUser(){
    return $this->findLast(array());
  }
}
