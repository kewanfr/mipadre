<?php $pageName = "clients" ?>
<script type="text/javascript" src="<?= Router::webroot("vendor/js/jquery.dataTables.min.js"); ?>"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<h2 class="text-center">Liste des Clients</h2>

<?= $this->Session->flash(); ?>
<a type="button" class="btn btn-info mb-4" href="<?= Router::url("admin/clients/edit/") ?>">Ajouter un client</a>
<div id='map' style='width:100%;height:60vh;margin-bottom: 20px;'></div>

<div class="modal fade" id="resetModal" tabindex="1" role="dialog" aria-labelledby="resetModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=resetModalLabel> Supprimer ce client </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"> &times; </span>
        </button>
      </div>
      <div class="modal-body">Voulez-vous remettre à 0 le nombre de bouteilles ?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Annuler </button>
        <form action="" method="POST">
          <button type="submit" class="btn btn-warning btn-block">Mettre à 0</button>
        </form>
      </div>
    </div>
  </div>
</div>

<table id="cavistes-table" class="table table-striped table-bordered table-hover display">
  <thead>
    <tr>
      <th scope="col" name="name">Nom</th>
      <th scope="col" name="bouteilles" style="width: 1%"><i class="fa-regular fa-bottle-droplet fa-fw"></i></th>
      <th scope="col" name="carte">

        <div class="flexnowrap">
          
          <i class="fa-solid fa-location-dot pr-2"></i>
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="allMapSwitch">
            <label class="custom-control-label" for="allMapSwitch"></label>
          </div>
        </div>
      </th>
      <th scope="col" name="adresse">Adresse</th>
      <th scope="col" name="actions">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clients as $key => $client) { ?>

      <tr>
        <td class="bold"><?= $client->name ?></td>
        <td><span class="badge badge-<?= $client->badgeColor ?>"><?= $client->nb_bouteilles ?></span>
        <td>
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch<?= $client->id ?>" <?= $client->showMap ? "checked" : "" ?>>
            <label class="custom-control-label" for="customSwitch<?= $client->id ?>"></label>
          </div>
        </td>
        <td><?= $client->adresse ?></td>
        <td>
          <div class="nowrap">
            <a type="button" class="btn btn-info btn-sm" href="<?= Router::url("admin/clients/edit/" . $client->id) ?>">Editer</a>
            <a type="button" class="btn btn-warning btn-sm" href="<?= Router::url("admin/clients/generateqr/" . $client->id) ?>">QR Code</a>
            <button type="button" class="btn btn-danger btn-sm reset-button" data-toggle="modal" data-target="#resetModal" data-id="<?= $client->id ?>"><i class="fa-regular fa-bottle-droplet fa-fw"></i> Reset</button>
          </div>
        </td>
      </tr>

    <?php } ?>

  </tbody>
</table>
<br>

<script>
  url = "<?= Router::url("admin/clients/resetBouteilles/") ?>";
  $(document).on('click', '.reset-button', function() {
    var id = $(this).data('id'); // Récupère l'ID du bouton

    $('#resetModal form').attr('action', url + id);
  });
</script>

<script>
  
  var map;
  var markers = []; 
  var infowindows = [];

  let addresses = <?= json_encode($addresses) ?>;

  let centerClient = addresses[0];
  Object.values(addresses).forEach(function(client) {
    if(client.nb_bouteilles > centerClient.nb_bouteilles) centerClient = client;
  }); // On cherche le client avec le plus de bouteilles pour centrer la carte dessus

  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: parseFloat(centerClient.lat), lng: parseFloat(centerClient.lon)},
      zoom: 8,
      mapTypeControl: false
    });

    let client = null;

    Object.values(addresses).forEach(function(client) { // On ajoute les markers sur la carte
      
      let text = client.mapName + " => " + client.nb_bouteilles + " bouteilles";
      let id = client.id.toString();

      let lat = parseFloat(client.lat);
      let lon = parseFloat(client.lon);
      let icon = "http://maps.google.com/mapfiles/ms/icons/" + client.markerColor + "-dot.png";

      markers[id] = new google.maps.Marker({
        position: {lat: lat, lng: lon},
        map: client.showMap == true ? map : null,
        icon: {
          url: icon,
        },
      });
      infowindows[id] = new google.maps.InfoWindow({
        content: text
      });
      markers[id].addListener('click', function() {
        infowindows[id].open(map, markers[id]);
      });

    });

  }
</script>

<script src='https://maps.googleapis.com/maps/api/js?key=<?= SecureConf::$googleMapsAPIKEY ?>&callback=initMap' async defer></script>
<script>
  $(document).ready(function() {
    $('#allMapSwitch').change(function() { // Lorsque l'on change l'état du switch "Afficher tous les clients sur la carte"

      $('#cavistes-table input[type="checkbox"]').prop('checked', this.checked); // On change l'état de tous les autres switchs
      
      markers.forEach(function(marker, index) { // On affiche ou non les markers sur la carte
        if ($('#allMapSwitch').is(':checked')) {
          marker.setMap(map);
        } else {
          marker.setMap(null);
        }
      });
    });

    $('#cavistes-table input[type="checkbox"]').change(function() { // Lorsque l'on change l'état d'un switch "Afficher sur la carte"
      var id = $(this).attr('id').replace('customSwitch', '');
      if ($(this).attr('id') == "allMapSwitch") { // On vérifie que ce n'est pas le switch "Afficher tous les clients sur la carte"
        return;
      }
      if (markers[id]) { // On affiche ou non le marker sur la carte
        if (!this.checked) {
          markers[id].setMap(null);
        } else {
          markers[id].setMap(map);
        }
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $("#cavistes-table").DataTable({
      language: {
        "url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/French.json"
      },
      pageLength: 50,
      columns: [{
          name: "name",
          orderable: true,
        },
        {
          name: "bouteilles",
          orderable: true,
        },
        {
          name: "carte",
          orderable: false,
        },
        {
          name: "adresse",
          orderable: false,
        },
        {
          name: "actions",
          orderable: false,
        },

      ],
      order: [
        [1, "desc"]
      ]
    });
  });
</script>