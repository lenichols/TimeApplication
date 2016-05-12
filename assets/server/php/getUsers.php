<?php

/*ini_set("display_errors",1); 
error_reporting(E_ALL);*/
header('Access-Control-Allow-Origin: *');  

include_once("config.php");

//try and connect if not send error in response
try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $rows = array();
        $stmt = $conn->query("SELECT * FROM users ORDER BY timeadded DESC");
        while($r = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $rows[] = $r;
        }
    
        print json_encode(array('userData' => $rows));
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}


?>