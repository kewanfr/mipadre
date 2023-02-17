<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Importer un fichier CSV dans MySQL</title>
</head>
<body>
    <h1>Importer un fichier CSV dans MySQL</h1>

    <form action="importer.php" method="post" enctype="multipart/form-data">
        <label for="fichier">Sélectionner un fichier CSV à importer :</label>
        <input type="file" name="fichier" id="fichier" accept=".csv">
        <br><br>
        <input type="submit" name="importer" value="Importer">
    </form>

</body>
</html>
<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "mipadre";
$username = "root";
$password = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Vérifier si un fichier a été soumis
if (isset($_POST["importer"])) {
    // Vérifier si le fichier est bien un fichier CSV
    $fichier = $_FILES["fichier"]["tmp_name"];
    $typeFichier = $_FILES["fichier"]["type"];
    if ($typeFichier === "text/csv") {
        // Ouverture du fichier CSV et insertion des données dans la base de données
        if (($handle = fopen($fichier, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $clt = $data[0];
                $name = $data[1];
                $adresse = $data[2];
                $mail = $data[3];
                $telephone = isset($data[4]) ? $data[4] : null;
                $nb_bouteilles = isset($data[5]) ? $data[5] : null;


                $sqlFields = "";
                $sqlValues = "";
                $valuesArr = array();

                if(!empty($clt)) {
                    $sqlFields .= "code";
                    $sqlValues .= "?";
                    // $sqlValues .= "\"".$clt."\"";
                    $valuesArr[] = $clt;
                }

                if(!empty($name)) {
                    $sqlFields .= ", name";
                    $sqlValues .= ", ?";
                    // $sqlValues .= ", \"".$name."\"";
                    $valuesArr[] = $name;
                }

                if(!empty($adresse)) {
                    $sqlFields .= ", adresse";
                    $sqlValues .= ", ?";
                    // $sqlValues .= ", \"".$adresse."\"";
                    $valuesArr[] = $adresse;
                }

                if(!empty($mail) && $mail != "NULL") {
                    $sqlFields .= ", mail";
                    $sqlValues .= ", ?";
                    // $sqlValues .= ", \"".$mail."\"";
                    $valuesArr[] = $mail;
                }

                if(!empty($telephone) && $telephone != "NULL") {
                    $sqlFields .= ", telephone";
                    $sqlValues .= ", ?";
                    // $sqlValues .= ", \"".$telephone."\"";
                    $valuesArr[] = $telephone;
                }

                if(!empty($nb_bouteilles) && $nb_bouteilles != "NULL") {
                    $sqlFields .= ", nb_bouteilles";
                    $sqlValues .= ", ?";
                    // $sqlValues .= ", \"".$nb_bouteilles."\"";
                    $valuesArr[] = $nb_bouteilles;
                }

                $sqlValues = str_replace("'", "\'", $sqlValues);


                $sql = "INSERT INTO clients (".$sqlFields.") VALUES (".$sqlValues.")";
                // $sql = "INSERT INTO clients2 (CLT, name, adresse, mail, telephone, nb_bouteilles) VALUES (?, ?, ?, ?, ?, ?)";
                echo $sql;
                $stmt = $pdo->prepare($sql);
                
                echo "<pre>";
                print_r($data);
                print_r($valuesArr);
                echo "</pre>";
                $stmt->execute($valuesArr);
            }
            fclose($handle);
            echo "Importation terminée.";
        } else {
            echo "Erreur : impossible d'ouvrir le fichier.";
        }
    } else {
        echo "Erreur : le fichier doit être de type CSV.";
    }
}
