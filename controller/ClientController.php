<?php

class ClientController extends Controller
{

  function add()
  {
    if(!$this->Session->read('Guest')){
      $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');
      $this->redirect('users/login');
    }
    $client = $this->Session->read('Guest');

    $this->loadModel('Client');

    $requestData = $this->Client->getClient($client->id, "id, name, nb_bouteilles");
    $requestData->nb_bouteilles = $requestData->nb_bouteilles + 1;
    $this->Client->save($requestData);

    $this->redirect('client/edit');
    $this->Session->setFlash('Une bouteille ajoutée !', 'success');
  }

  function edit()
  {
    if (!$this->Session->read('Guest') || !$this->Session->read('Guest')->id) {
      $this->Session->setFlash('Vous devez être connecté pour accéder à cette page', 'danger');
      $this->redirect('users/login');
    }
    $client = $this->Session->read('Guest');

    $this->loadModel('Client');
    $client = $this->Client->getClient($client->id, "id, name, nb_bouteilles, updated");
    if ($this->request->data) {
      $this->Client->save((object) array(
        'id' => $client->id,
        'nb_bouteilles' => $this->request->data->nb_bouteilles,
      ));
      $this->Session->setFlash('Nombre de bouteilles mis à jour avec succès !', 'success');
    }
    $client = $this->Client->getClient($client->id, "id, name, nb_bouteilles, updated");

    $d['client'] = $client;
    $d['id'] = $client->id;
    $d['title'] = $client->name;

    $this->set($d);
    $this->render("edit");
  }

  function qrlogin($client_id = null, $token = null)
  {

    if ($client_id == null || $token == null) {
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
    if ($guest) {
      $this->loadModel('Client');
      $client = $this->Client->findFirst(array(
        'conditions' => array(
          'id' => $client_id
        )
      ));
      if ($client) {
        $this->Session->write('Guest', $client);
        $this->redirect('client/add');
      } else {
        $this->Session->setFlash('Erreur de connexion', 'danger');
        $this->redirect('users/login');
      }
    } else {
      $this->Session->setFlash('Erreur de connexion', 'danger');
      $this->redirect('users/login');
    }
  }
}
?>