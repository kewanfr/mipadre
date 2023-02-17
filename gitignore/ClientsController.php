<?php

class ClientsController extends Controller
{

  /**
   * ADMIN Functions
   */
  
  function admin_generateqr($id){

    $this->loadModel('Guest');
    $this->loadModel('Client');
    $d['client'] = $this->Client->findFirst(
      array(
        'conditions' => array(
          'id' => $id
        )
      )
    );
    if(empty($d['client'])){
      $this->e404('Cette page n\'existe pas');
    }

    $d['guest'] = $this->Guest->findFirst(
      array(
        'conditions' => array(
          'client_id' => $id
        )
      )
    );
    if(empty($d['guest'])){
      $guest = $this->Guest->save(array(
        'client_id' => $id,
        'QRToken' => generateToken(Conf::$QRTokenLength)
      ));
      $d['guest'] = $this->Guest->findFirst(
        array(
          'conditions' => array(
            'client_id' => $id
          )
        )
      );
    }
    $d['client_id'] = $d['guest']->client_id;
    $d['QRToken'] = $d['guest']->QRToken;
    $d['title'] = "QR Code de ".$d['client']->name;

    $this->set($d);
  }

  function admin_edit($id = null){
    $this->loadModel('Client');

    if(isset($id)){
      $d['mode'] = "edit";
      $d['id'] = $id;
    }else {
      $d['mode'] = "add";
      $d['title'] = "Ajouter un client";
    }

    if($this->request->data){
      
      $d['title'] = "Modifier ".$this->request->data->name;
      if($d['mode'] == "edit"){
        $this->request->data->id = $id;
        $this->Client->save($this->request->data);
        $this->Session->setFlash("Informations modifiées avec succès !");
      }else{
        $newID = $this->Client->save($this->request->data);
        $this->request->data->id = $newID;
        $this->Session->setFlash("Client créé avec succès !", "success", 2);
        $this->redirect('admin/clients/edit/'.$newID);
      }
      $d['mode'] = "edit";

    }else if($d['mode'] == "edit"){
        $this->request->data = $this->Client->findFirst(
          array(
            'conditions' => array(
              'id' => $id
            )
            )
          );
        if(empty($this->request->data)){
          $this->e404('Cette page n\'existe pas');
        }
        $d['title'] = "Modifier ".$this->request->data->name;
      }


    $this->set($d);
  }

  function admin_delete($id){

    $this->loadModel('Client');
    $client = $this->Client->findFirst(
      array(
        'conditions' => array(
          'id' => $id
        )
        )
      );
    if(empty($client)){
      $this->e404('Cette page n\'existe pas');
    }

    $this->Client->delete($id);
    $this->Session->addFlashMessage($client->name." (ID:".$id.") Supprimé avec succès !");
    $this->redirect('admin/clients/');

  }

  function admin_index(){
    $this->admin_list();
    $this->render("admin_list");
  }

  function admin_list(){
    $this->loadModel('Client');
    $d['clients'] = $this->Client->find(array());
    $addresses = array();

    foreach ($d['clients'] as $client) {
      $info_supp = $client->name . ", " . $client->nb_bouteilles . " consignes";
      $addresses[$client->adresse] = $info_supp;
    }
    $d['addresses'] = $addresses;
    $this->set($d);
  }
}
?>