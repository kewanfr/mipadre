<?php $pageName = "clients" ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<h2 class="text-center">Liste des Clients</h2>
<a type="button" class="btn btn-info mb-4" href="<?= Router::url("admin/clients/edit/") ?>">Ajouter un client</a>

<table id="cavistes-table" class="table table-striped table-bordered table-hover display">
  <thead>
    <tr>
      <th scope="col" name="name">Nom</th>
      <th scope="col" name="bouteilles" style="width: 1%">Bouteilles</th>
      <th scope="col" name="carte">Carte</th>
      <th scope="col" name="adresse">Adresse</th>
      <th scope="col" name="actions" style="max-width: 9vw">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clients as $key => $client) { ?>
      <?php
        if($client->nb_bouteilles == 0){
          $client->show = false;
          $badgeColor = "success";
        }else if($client->nb_bouteilles < 3){
          $client->show = true;
          $badgeColor = "warning";
        }else{
          $client->show = true;
          $badgeColor = "danger";
        }
        ?>
      <!-- onclick="window.location.href = <= '\'' . Router::url('admin/clients/edit/' . $client->id) . '\'' ?>;" -->
      <tr >
        <td class="bold"><?= $client->name ?></td>
        <td><span class="badge badge-<?= $badgeColor ?>"><?= $client->nb_bouteilles ?></span></td>
        <td>
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch<?= $client->id ?>" <?= $client->show ? "checked" : "" ?>>
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
<?php
echo "<div id='map' style='width:100%;height:400px;'></div>";
echo "<script>";
echo "var map;";
echo "var markers = [];";
echo "var infowindows = [];";
echo "function initMap() {";
echo "  map = new google.maps.Map(document.getElementById('map'), {";
echo "    center: {lat: 47.0450268, lng: -1.2626808},";
echo "    zoom: 10,";
echo "    mapTypeControl: false";
echo "  });";

$counter = 0;

// Boucle sur les adresses pour afficher les marqueurs
foreach ($addresses as $address => $text) {
  $index = array_search($address, array_column($clients, 'adresse'));
  $id = $clients[$index]->id;
  // debug($clients[$id]);
  // debug($clients->($id - 1));

  $text = str_replace("'", "\'", $text);
  $coordinates = getCoordinatesFromAddress($address);

  // bottle color depending on number of bottles
  if($clients[$index]->nb_bouteilles == 0){
    $img = "http://localhost/mipadre/img/bottle.png";
  }else {
    $img = "http://localhost/mipadre/img/bottle_red.png";
  }

  echo "  markers.push(new google.maps.Marker({";
  echo "    position: {lat: " . $coordinates['lat'] . ", lng: " . $coordinates['lng'] . "},";
  echo "    map: map,";
  echo "    icon: {";
  echo "      url: '".$img."',";
  echo "      scaledSize: new google.maps.Size(40, 40)";
  echo "    }";
  echo "  }));";
  echo "  infowindows.push(new google.maps.InfoWindow({";
  echo "    content: '" . $text . "'";
  echo "  }));";
  echo "  markers[" . $counter . "].addListener('mouseover', function() {";
  echo "    infowindows[" . $counter . "].open(map, markers[" . $counter . "]);";
  echo "  });";
  $counter++;
}
echo "}";
echo "</script>";
echo "<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyAS4GctTl7DG8MjHEMOAOaqbbHtaa1Q4T0&callback=initMap' async defer></script>";

?>
<script>

  $(document).ready(function() {
    markers.forEach(function(marker, index) {
      if (!$('#customSwitch' + <?= json_encode(array_column($clients, 'id')) ?>[index]).is(':checked')) {
        marker.setMap(null);
      }
    });

    $('#cavistes-table input[type="checkbox"]').change(function() {
      var id = $(this).attr('id').replace('customSwitch', '');
      var index = <?= json_encode(array_column($clients, 'id')) ?>.indexOf(parseInt(id));
      if (!this.checked) {
        markers[index].setMap(null);
      } else {
        markers[index].setMap(map);
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
      pageLength: 25,
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