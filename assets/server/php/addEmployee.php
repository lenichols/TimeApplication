<?php

if (isset($_POST["addEmployee"])) {
    //try and connect if not send error in response
    try {
        $emName = $_POST["empName"];
        $emCode = $_POST["empCode"];
        $emUserN = $_POST["empUN"];
        $sendSup = $_POST["supChosen"];
        $emPass = "4a44337eaefa0bf1c26f9119ae9d243b080f71b0d6717812cd685d240498501c";
                
        $now = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
		$tadded = $now->format('Y-m-d H:i:s'); 
        
        $emp = $conn->prepare('INSERT INTO users (displayName, eID, userName, pass, supName, role, dateadded, active) VALUES (?,?,?,?,?,?,?,?)');
            $emres = $emp->execute(array($emName, $emCode, $emUserN, $emPass, $sendSup, 'basic', $tadded, 1));

            if($emres){
                  echo '<script>
						    window.location = "../../../dashboard/admin.php?auth='.$adminAuth.'&id='.$uid.'&empAdded=true";
						</script>';
            } else {
                  echo "<span style='color: red;'><i>Ooops! Something bad just happened...</i></span>";
            }

    } catch(PDOException $e) {
          echo $e->getMessage();
    }

}

?>