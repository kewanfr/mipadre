<?php 
$url = SecureConf::$ExternalUrl.Router::url("qr/$client_id/$QRToken");

$qc = new QRCode();
$qc->URL($url);
?>

<h1><?= $title ?></h1>
<p><a href="<?= $url ?>" target="_blank">Lien du qr code</a></p>
<img src="<?= $qc->QRCODEURL(300); ?>" alt="QR Code">

  