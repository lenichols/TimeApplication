<?php

try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $supsStmt = $conn->query("SELECT displayName FROM users WHERE role = 'sup'");
        
        while($g = $supsStmt->fetch()) {
                $supFoundName = $g["displayName"];
                echo '<option value="'.$supFoundName.'">'.$supFoundName.'</option>';
        }
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}

?>