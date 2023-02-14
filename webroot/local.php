<form action="" method="get">
  <label for="threshold">Afficher seulement si la consigne est supérieure à</label>
  <input type="number" name="threshold" id="threshold" value="<?php echo (isset($_GET['threshold'])) ? $_GET['threshold'] : ''; ?>">
  <input type="submit" value="Trier">
</form>

<?php
// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "mp");

// Récupération du seuil défini par l'utilisateur
$threshold = isset($_GET['threshold']) ? $_GET['threshold'] : 1;

// Requête pour récupérer les adresses et les informations supplémentaires des clients
// avec un nombre de bouteilles supérieur au seuil défini
$query = "SELECT adresse, name, nb_bouteilles FROM clients WHERE nb_bouteilles > ".$threshold;
$result = mysqli_query($conn, $query);

// Tableau pour stocker les adresses et les informations supplémentaires des clients
$addresses = array();

// Boucle sur les résultats pour ajouter les adresses et les informations supplémentaires au tableau
while ($row = mysqli_fetch_assoc($result)) {
  $info_supp = str_replace("'", "\'", $row['name']) . ", " . $row['nb_bouteilles'] . " consignes";
  // print_r($info_supp);
  $addresses[$row['adresse']] = $info_supp;
}
// print_r($addresses);

// Fermeture de la connexion à la base de données
mysqli_close($conn);

echo "<div id='map' style='width:100%;height:400px;'></div>";
echo "<script>";
echo "var map;";
echo "var markers = [];";
echo "var infowindows = [];";
echo "function initMap() {";
echo "  map = new google.maps.Map(document.getElementById('map'), {";
  echo "    center: {lat: 47.0450268, lng: -1.2626808},";
echo "    zoom: 8";
echo "  });";

// Compteur pour indexer les marqueurs et les info-bulles
$counter = 0;

// Boucle sur les adresses pour afficher les marqueurs
  foreach ($addresses as $address => $text) {
  $coordinates = get_coordinates_from_address($address);
    echo "  markers.push(new google.maps.Marker({";
    echo "    position: {lat: ".$coordinates['lat'].", lng: ".$coordinates['lng']."},";
    echo "    map: map,";
    echo "    icon: {";
    echo "      url: 'http://localhost/mipadre/img/bottle.png',";
    echo "      scaledSize: new google.maps.Size(40, 40)";
    echo "    }";
    echo "  }));";
    echo "  infowindows.push(new google.maps.InfoWindow({";
    echo "    content: '".$text."'";
    echo "  }));";
    echo "  markers[".$counter."].addListener('mouseover', function() {";
      echo "    infowindows[".$counter."].open(map, markers[".$counter."]);";
    echo "  });";
    $counter++;
  }
  echo "}";
  echo "</script>";
  echo "<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyAS4GctTl7DG8MjHEMOAOaqbbHtaa1Q4T0&callback=initMap' async defer></script>";
  
  // Fonction pour obtenir les coordonnées GPS à partir de l'adresse
  function get_coordinates_from_address($address) {
      $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyAS4GctTl7DG8MjHEMOAOaqbbHtaa1Q4T0";
    
      // Initialisation de cURL
      $ch = curl_init();
      
      // Configuration de l'URL et d'autres options
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      // Exécution de la requête et stockage de la réponse
      $response = curl_exec($ch);
      
      // Fermeture de la session cURL
      curl_close($ch);
      
      // Décodage de la réponse en tableau PHP
      $response = json_decode($response, true);
      
      // Renvoi des coordonnées
      return $response['results'][0]['geometry']['location'];
    }
    
  
  ?>