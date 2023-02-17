<?php
class Client extends Model
{

  // public $table = 'clients';

  public function getClient($id, $fields)
  {
    return $this->findFirst(array(
      'fields' => $fields,
      'conditions' => array(
        'id' => $id
      )
    ));
  }
}

?>