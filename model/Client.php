<?php
class Client extends Model
{
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