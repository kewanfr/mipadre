<?php

class ClientController extends Controller
{

  function edit()
  {
    if(!$this->Session->read('Guest')){
      $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');
      $this->redirect('users/login');
    }
    $client = $this->Session->read('Guest');

    $this->loadModel('Client');

    $requestData = $this->Client->findFirst(array(
      'conditions' => array(
        'id' => $client->id
      )
    ));
    $requestData->nb_bouteilles = $requestData->nb_bouteilles + 1;
    $this->Client->save($requestData);
    
    $d['id'] = $client->id;
    $d['client'] = $this->Client->findFirst(array(
      'conditions' => array(
        'id' => $client->id
      )
    ));
    $d['title'] = $d['client']->name;

    $this->set($d);

    $this->redirect('client/show');
    $this->Session->setFlash('Une bouteille ajoutée !', 'success');
  }

  function show()
  {
    if(!$this->Session->read('Guest')){
      $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');
      $this->redirect('users/login');
    }
    $client = $this->Session->read('Guest');

    $this->loadModel('Client');
    $requestData = $this->Client->findFirst(array(
      'conditions' => array(
        'id' => $client->id
      )
    ));

    $d['id'] = $client->id;
    $d['client'] = $this->Client->findFirst(array(
    'conditions' => array(
      'id' => $client->id
      )
    ));
    $d['title'] = $d['client']->name;

    $this->set($d);
    $this->render("edit");
  }

  function add()
  {
    if(!$this->Session->read('Guest')){
      $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');
      $this->redirect('users/login');
    }
    $client = $this->Session->read('Guest');
    $this->loadModel('Client');

    if($this->request->data){
      $this->Client->save($this->request->data);
      $this->Session->setFlash('Nombre de bouteilles mis à jour avec succès !', 'success');
    }

    $d['id'] = $client->id;
    $d['client'] = $this->Client->findFirst(array(
      'conditions' => array(
        'id' => $client->id
      )
    ));
    $d['title'] = $d['client']->name;

    $this->set($d);
  }

  
  function qrlogin($client_id = null, $token = null){

    if($client_id == null || $token == null){
      $this->Session->setFlash('Erreur de connexion', 'danger');
      $this->redirect('users/login');
    }

    $this->loadModel('Guest');
    $guest = $this->Guest->findFirst(array(
      'conditions' => array(
        'client_id' => $client_id,
        'QRToken' => $token
      )
    ));
    if($guest){
      $this->loadModel('Client');
      $client = $this->Client->findFirst(array(
        'conditions' => array(
          'id' => $client_id
        )
      ));
      if($client){
        $this->Session->write('Guest', $client);
        $this->redirect('client/edit');
      }else{
        $this->Session->setFlash('Erreur de connexion', 'danger');
        $this->redirect('users/login');
      }
    }else{
      $this->Session->setFlash('Erreur de connexion', 'danger');
      $this->redirect('users/login');
    }
  }

}
?>