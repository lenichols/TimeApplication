<?php

$full_url  = $_SERVER['REQUEST_URI'];

define("SERVICES_INNER", dirname(dirname(dirname(dirname(__FILE__)))));
define("SERVICES_ROOT", $_SERVER["DOCUMENT_ROOT"].'/');
//define("SERVICES", dirname("/assets/services/php/"));
define("CONFIG", dirname(__FILE__)."../assets/server/php/config.php");

?>