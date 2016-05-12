<?php

ini_set("display_errors",1); 
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');  


try {
        include_once("config.php");
    
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $statement = $conn->query("SELECT name FROM clients");

        $ClientJson = $statement->fetchAll(PDO::FETCH_ASSOC);
        print json_encode(array('clientData' => $ClientJson));
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}

?>