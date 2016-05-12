<?php

ini_set("display_errors",1); 
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');  

include_once("config.php");
//try and connect if not send error in response
session_start();

try {

   $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);

   $nm = $_POST['name'];
   $pass = hash('sha256', $_POST['pass']);

   $stmt = $conn->prepare("SELECT * FROM users WHERE userName = ? AND active = 1 LIMIT 1");
   $stmt->execute(array($nm));
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $count = $stmt->rowCount();

   if($row) {
  
       if($row['pass']==$pass){
	        
			if (!$conn) {
			  		die('Could not connect: ' . mysql_error());
			} else {
					$now = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
					$tadded = $now->format('Y-m-d H:i:s'); 
					$sth = $conn->prepare("UPDATE users SET lastaccess = ? WHERE userName = ?");
					$sth->execute(array($tadded, $nm));

            $_SESSION['uid'] = $row['id'];
            $_SESSION['DisplayAs'] = $row['displayName'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['empID'] = $row['eID'];
            $_SESSION['supervisor'] = $row['supName'];

            if($row['role'] === "admin"){
                
                $_SESSION['authenticated'] = "admin";
                
                header('Location: ../../../dashboard/admin.php?auth='.$_SESSION['authenticated'].'&id='.$row['id']);
                
            } elseif ($row['role'] === "sup"){
                
                $_SESSION['authenticated'] = "sup";
                
                header('Location: ../../../dashboard/sup.php?auth='.$_SESSION['authenticated'].'&id='.$row['id']);
                
            } else {
                header('Location: ../../../dashboard/dashboard.php?id='.$row['id']);
            }
          }
       } else{
            header('Location: ../../../cp.php?res=wp');
       }
       
   } else {
       header('Location: ../../../cp.php?res=na');
   }

} catch(PDOException $e) {
      echo $e->getMessage();
}


              







?>