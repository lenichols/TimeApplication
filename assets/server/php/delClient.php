<?php

if(isset($_POST['cliCheckbox'])){  
    try {
        $clID = $_POST['cliCheckbox'];
        $q = array_filter($clID);
        for($e=0;$e<=count($q)-1;$e++) {
            
            $st = $conn->prepare("DELETE from clients WHERE id = ?");
            $s = $st->execute(array($q[$e]));

            if($s){ echo "<span style='color: darkblue;'><i>Client(s) deleted!</i></span>"; echo "<meta http-equiv='refresh' content='0'>"; } 
        }
    } catch(PDOException $e) {
    echo $e->getMessage();
    }
} 
?>