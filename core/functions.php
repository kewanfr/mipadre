<?php
include 'functions/QRCode.php';

function debug($var)
{

  if (Conf::$debug > 0) {

    $debug = debug_backtrace();
    echo '<p>&nbsp;</p><p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle()"><strong>' . $debug[0]['file'] . '</strong> l.' . $debug[0]['line'] . '</a></p>';
    echo '<ol style="display: none;">';
    foreach ($debug as $k => $v) {
      if ($k > 0) {
        echo '<li><strong>' . $v['file'] . '</strong> l.' . $v['line'] . '</li>';
      }
    }
    echo '</ol>';
    echo '<pre>';
    print_r($var);
    echo '</pre>';
  }
}

function generateToken($length = 32)
{
  $token = bin2hex(random_bytes($length));
  return $token;
}

function generateQR($id, $token)
{
  $URI = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
  $code = SecureConf::$ExternalUrl . Router::url("users/qrlogin/$id/$token");
  $generateUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $code . '&choe=UTF-8';
  return $generateUrl;
}

function getCoordinatesFromAddress($address)
{
  $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . SecureConf::$googleMapsAPIKEY;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);

  $response = json_decode($response, true); // Décodage de la réponse en tableau PHP
  return isset($response['results'][0]['geometry']['location']) ? $response['results'][0]['geometry']['location'] : false; // Renvoi des coordonnées
}

function RelativeDate($timestampStr)
{
  $dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
  $now = time();
  $timestamp = strtotime($timestampStr);
  $diff = $now - $timestamp;

  if (date('Y-m-d', $timestamp) == date('Y-m-d', $now)) {
    return "aujourd'hui";
  } elseif (date('Y-m-d', $timestamp) == date('Y-m-d', strtotime("-1 day"))) {
    return "hier";
  } else {
    $dateFormatter->setPattern('d MMMM');
    return "le " . $dateFormatter->format($timestamp);
  }
}

function RelativeDatetime($timestampStr)
{
  $dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
  $now = time();
  $timestamp = strtotime($timestampStr);
  $diff = $now - $timestamp;

  if (date('Y-m-d', $timestamp) == date('Y-m-d', $now)) {
    return "aujourd'hui à " . date("H\hi", $timestamp);
  } elseif (date('Y-m-d', $timestamp) == date('Y-m-d', strtotime("-1 day"))) {
    return "hier à " . date("H\hi", $timestamp);
  } else {
    $dateFormatter->setPattern('d MMMM');
    return "le " . $dateFormatter->format($timestamp) . " à " . date("H\hi", $timestamp);
  }
}
?>