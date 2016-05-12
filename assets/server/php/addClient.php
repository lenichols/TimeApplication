<?php

if (isset($_POST["addClient"])) {
    //try and connect if not send error in response
    try {
        $clientname = $_POST["addClientName"];
        $pdfReq = (!empty($_POST['clientPDFReg'])) ? $_POST['clientPDFReg'] : null;
        
        $stmt = $conn->prepare('INSERT INTO clients (name,pdf) VALUES (?,?)');
            $result = $stmt->execute(array($clientname,$pdfReq));

            if($result){
                  echo '<script>
						    window.location = "../../../dashboard/admin.php?auth='.$adminAuth.'&id='.$uid.'&cliAdded=true";
						</script>';
            } else {
                  echo "<span style='color: red;'><i>Ooops! Something bad just happened...</i></span>";
            }

    } catch(PDOException $e) {
          echo $e->getMessage();
    }
}
    
        $clientS = $conn->query("SELECT id, name FROM clients");
        echo "<br/><div><b style='color: #FFF;'>CLIENTS</b> <small style='color: #FFF;'>(Tick to delete):</small><br/>";
        while($c = $clientS->fetch()) {
            //$rows[] = $r;  
                $clnt = $c["name"];
                $clID = $c["id"];
                echo '<input name="cliCheckbox[]" id="'.$clID.'" type="checkbox" class="checkboxReset" value="'.$clID.'" >&nbsp;&nbsp;'.$clnt.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        echo "</div>";

?>