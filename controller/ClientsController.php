<?php

class ClientsController extends Controller
{

  /**
   * ADMIN Functions
   */

  function admin_generateqr($id)
  {

    $this->loadModel('Guest');
    $this->loadModel('Client');
    $d['client'] = $this->Client->getClient($id, "id, code, name");
    $code = $d['client']->code;
    if (empty($d['client']) || empty($code)) {
      $this->e404('Cette page n\'existe pas');
    }

    $d['guest'] = $this->Guest->findFirst(
      array(
        'conditions' => array(
          'client_id' => $code
        )
      )
    );
    if (empty($d['guest'])) {
      $guest = $this->Guest->save(array(
        'client_id' => $code,
        'QRToken' => generateToken(Conf::$QRTokenLength)
      ));
      $d['guest'] = $this->Guest->findFirst(
        array(
          'conditions' => array(
            'client_id' => $code
          )
        )
      );
    }
    $d['client_id'] = $d['guest']->client_id;
    $d['QRToken'] = $d['guest']->QRToken;
    $d['title'] = "QR Code de " . $d['client']->name;

    $this->set($d);
  }

  function admin_siteqr()
  {
  }

  function admin_edit($id = null)
  {
    $this->loadModel('User');
    $this->loadModel('Client');

    if (isset($id)) {
      $d['mode'] = "edit";
      $d['id'] = $id;
    } else {
      $d['mode'] = "add";
      $d['title'] = "Ajouter un client";
      // $lastCode = $this->Client->
      $lastClient = $this->Client->getLastClient();
      $lastCode = $lastClient->code;
      $nextCode = str_replace("CLT", "", $lastCode);
      $nextCode = intval(ltrim($nextCode, "0")) + 1;
      $nextCode = "CLT" . $nextCode;
    }

    if ($this->request->data) {

      $d['title'] = "Modifier " . $this->request->data->name;
      $code = $this->request->data->code;
      $code = str_replace("CLT", "", $code);
      $code = intval(ltrim($code, "0"));
      $this->request->data->code = "CLT" . $code;

      if ($d['mode'] == "edit") {
        $this->request->data->id = $id;
        $this->Client->save($this->request->data);
        $this->Session->setFlash("Informations modifiées avec succès !");
      } else {
        $newID = $this->Client->save($this->request->data);
        $this->request->data->id = $newID;
        $this->Session->setFlash("Client créé avec succès !", "success", 2);
        $this->redirect('admin/clients/edit/' . $newID);
      }
      $d['mode'] = "edit";
    } else if ($d['mode'] == "edit") {
      $this->request->data = $this->Client->getClient($id, "id, code, name, mail, adresse, telephone, nb_bouteilles, user_id");
      if (empty($this->request->data)) {
        $this->e404('Cette page n\'existe pas');
      }
      $d['title'] = "Modifier " . $this->request->data->name;
    }
    $users = $this->User->getUsers("id, firstname, login, email, role");
    $d['users'] = array(
      " "
    );
    foreach ($users as $key => $user) {
      $d['users'][$user->id] = $user->firstname." (" . $user->login . ")";
    }
    if(!$this->request->data) $this->request->data = (object) array();
    if(isset($nextCode)) $this->request->data->code = $nextCode;
 
    $this->set($d);
  }

  function admin_delete($id)
  {

    $this->loadModel('Client');
    $client = $this->Client->getClient($id, "id, name");
    if (empty($client)) {
      $this->e404('Cette page n\'existe pas');
    }

    $this->Client->delete($id);
    $this->Session->addFlashMessage($client->name . " (ID:" . $id . ") Supprimé avec succès !");
    $this->redirect('admin/clients/');
  }

  function admin_resetBouteilles($id)
  {
    $this->loadModel('Client');
    $client = $this->Client->getClient($id, "id, name");
    if (empty($client)) {
      $this->e404('Cette page n\'existe pas');
    }

    $this->Client->save((object) array(
      'id' => $id,
      'nb_bouteilles' => 0
    ));
    $this->Session->addFlashMessage("Nombre de bouteilles de " . $client->name . " (ID:" . $id . ") remis à 0 avec succès !");
    $this->redirect('admin/clients/');
  }

  function admin_index()
  {
    $this->admin_list();
    $this->render("admin_list");
  }

  function admin_list()
  {
    $this->loadModel('Client');
    $d['clients'] = $this->Client->find(array("fields" => "id, code, name, mail, adresse, telephone, nb_bouteilles, lat, lon"));
    $addresses = array();

    foreach ($d['clients'] as $key => $client) {

      if ((empty($client->lat) || empty($client->lon)) && !empty($client->adresse)) {
        $coordinates = getCoordinatesFromAddress($client->adresse);
        if ($coordinates) {
          $this->Client->save((object) array(
            'id' => $client->id,
            'lat' => $coordinates['lat'],
            'lon' => $coordinates['lng']
          ));
        }
      }

      if (empty($client->name)) {
        $client->name = "Client sans nom";
      }

      if (empty($client->nb_bouteilles)) {
        $this->Client->save((object)array(
          'id' => $client->id,
          'nb_bouteilles' => 0
        ));
      }

      if ($client->nb_bouteilles >= 3) {
        $client->showMap = true;
        $client->badgeColor = "danger";
        $client->markerColor = "red";
      } else if ($client->nb_bouteilles >= 1) {
        $client->showMap = true;
        $client->badgeColor = "warning";
        $client->markerColor = "orange";
      } else {
        $client->showMap = false;
        $client->badgeColor = "success";
        $client->markerColor = "green";
      }

      $client->mapName = str_replace("'", "\'", $client->name);
      if (!empty($client->lat) && !empty($client->lon) && !empty($client->adresse)) {
        $addresses[$key] = $client;
      }
    }
    $d['addresses'] = $addresses;
    $this->set($d);
  }

  function admin_agents()
  {
  }
}
