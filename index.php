<?php

require_once("core/router.php");
$params = $_SERVER["REQUEST_URI"];
var_dump($_SERVER);
echo $params;

require_once("includes/header.php");
require_once("pages/$params.php");
require_once("includes/footer.php");

?>