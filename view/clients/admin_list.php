<?php $pageName = "clients" ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<h2 class="text-center">Liste des Clients</h2>
<a type="button" class="btn btn-info mb-4" href="<?= Router::url("admin/clients/edit/") ?>">Ajouter un client</a>

<div id='map' style='width:100%;height:50vh;margin-bottom: 20px;'></div>

<table id="cavistes-table" class="table table-striped table-bordered table-hover display">
  <thead>
    <tr>
      <th scope="col" name="name">Nom</th>
      <th scope="col" name="bouteilles" style="width: 1%">Bouteilles</th>
      <th scope="col" name="carte">Carte
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="allMapSwitch">
            <label class="custom-control-label" for="allMapSwitch"></label>
          </div></th>
      <th scope="col" name="adresse">Adresse</th>
      <th scope="col" name="actions" style="max-width: 9vw">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clients as $key => $client) { ?>

      <!-- onclick="window.location.href = <= '\'' . Router::url('admin/clients/edit/' . $client->id) . '\'' ?>;" -->
      <tr >
        <td class="bold"><?= $client->name ?></td>
        <td><span class="badge badge-<?= $client->badgeColor ?>"><?= $client->nb_bouteilles ?></span></td>
        <td>          
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch<?= $client->id ?>" <?= $client->showMap ? "checked" : "" ?>>
            <label class="custom-control-label" for="customSwitch<?= $client->id ?>"></label>
          </div>
        </td>
        <td><?= $client->adresse ?></td>
        <td>
          <a type="button" class="btn btn-info btn-sm" href="<?= Router::url("admin/clients/edit/" . $client->id) ?>">Editer</a>
          <a type="button" class="btn btn-warning btn-sm" href="<?= Router::url("admin/clients/generateqr/" . $client->id) ?>">QR Code</a>
        </td>
      </tr>

    <?php } ?>

  </tbody>
</table>
<br>

<script>
  
  var map;
  var markers = []; 
  var infowindows = [];
  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 47.0450268, lng: -1.2626808},
      zoom: 10,
      mapTypeControl: false
    });
    <?php
    $counter = 0;

    // Boucle sur les adresses pour afficher les marqueurs
    foreach ($addresses as $key => $client) {
      $text = $client->mapName." => ".$client->nb_bouteilles." bouteilles";
      $id = $client->id;

      $lat = $client->lat;
      $lon = $client->lon;

      $icon = "http://maps.google.com/mapfiles/ms/icons/" . $client->markerColor . "-dot.png";
      ?>
      markers[<?= $client->id ?>] = new google.maps.Marker({
        position: {lat: <?= $lat ?>, lng: <?= $lon ?>},
        map: <?= $client->showMap == true ? "true" : "false" ?> == true ? map : null,
        icon: {
          url: "<?= $icon ?>",
        },
      });
      infowindows[<?= $client->id ?>] = new google.maps.InfoWindow({
        content: '<?= $text ?>'
      });
      markers[<?= $client->id ?>].addListener('click', function() {
        infowindows[<?= $client->id ?>].open(map, markers[<?= $client->id ?>]);
      });
      

    <?php $counter++;
    }?>

  }
</script>

<script src='https://maps.googleapis.com/maps/api/js?key=<?= SecureConf::$googleMapsAPIKEY ?>&callback=initMap' async defer></script>
<script>

  $(document).ready(function() {
    markers.forEach(function(marker, index) {
      if ($('#customSwitch' + <?= json_encode(array_column($clients, 'id')) ?>[index]).is(':checked')) {
        marker.setMap(map);
      }else {
        marker.setMap(null);
      }
    });
 
    $('#allMapSwitch').change(function() {

      $('#cavistes-table input[type="checkbox"]').prop('checked', this.checked);
  
      markers.forEach(function(marker, index) {
        if ($('#allMapSwitch').is(':checked')) {
          marker.setMap(map);
        }else {
          marker.setMap(null);
        }
      });
    });

    $('#cavistes-table input[type="checkbox"]').change(function() {
      var id = $(this).attr('id').replace('customSwitch', '');
      if ($(this).attr('id') == "allMapSwitch") {
        return;
      }
      if (!this.checked) {
        markers[id].setMap(null);
      } else {
        markers[id].setMap(map);
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
      columns: [
        {
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
      order: [[ 1, "desc" ]]
    });
  });
</script>