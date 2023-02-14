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

?>