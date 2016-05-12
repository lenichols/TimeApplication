<?php

//try and connect if not send error in response
try {
        $supervisorsName = $_SESSION['DisplayAs'];
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $stmtSubmitted = $conn->prepare("SELECT * FROM records WHERE supqueue = :supName AND status != 'approved' and status!= 'exported' and status != 'denied' and status != 'deleted' ORDER BY inspector");
        $stmtSubmitted->execute(array(":supName" => $supervisorsName));
        
        while($r = $stmtSubmitted->fetch(PDO::FETCH_ASSOC)) {
            //$rows[] = $r;  
                $foundUUID = $r["uuid"];
                $inspector = $r["inspector"];
                $regHrs = $r["stHours"];
                $otHrs = $r["otHours"];
                $dtHrs = $r["dtHours"];
                $totalHrs = $r["hrsTotal"];
                $numwr = $r["wrnum"];
                $prjFound = $r["project"];
                $tsk = $r["taskType"];
                $pID = $r["id"];
                $stateStatus = $r["status"];
                $dtSub = date("m-d-Y", strtotime($r["date"]));
                $selDate = $r["date"];
                
	                echo '
	                <tr class="">
	                    <td align="center"><input type="checkbox" name="recCheckBx[]" value="'.$pID.'" style="width: 8px !important;"></td><td align="center">'.$dtSub.'</td>
	                    <td align="center">'.$inspector.'</td>
	                    <td align="center">'.$numwr.'</td>
	                    <td align="center">'.$prjFound.'</td>
	                    <td align="center">'.$tsk.'</td>
	                    <td align="center">'.$regHrs.'</td>
	                    <td align="center">'.$otHrs.'</td>
	                    <td align="center">'.$dtHrs.'</td>
	                    <td align="center">'.$totalHrs.'</td>
	                    <td align="center" class="foundStatus">';
	                        if($stateStatus == "approved") { 
	                            echo "<span style='color: #1D5F9E;'><b>".$stateStatus."</b></span>"; 
	                        } elseif ($stateStatus == "submitted"){
	                            echo "<span style='color: #0D7E0B;'>".$stateStatus."</span>"; 
	                        } elseif ($stateStatus == "re-submitted"){
	                            echo "<span style='color: #E39608;'>".$stateStatus."</span>"; 
	                        } elseif ($stateStatus == "denied"){
	                            echo "<span style='color: orange;'>".$stateStatus."</span>"; 
	                        } else { echo "<span style='color: #333;'>".$stateStatus."</span>";  }
	                    echo '</td><input type="hidden" name="selID[]" value="'.$foundUUID.'"><input type="hidden" name="selDate[]" value="'.$selDate.'"></tr>';
                    //echo "<hr style='border: 1px solid black' />";
                    
                   
                    
                    
        }
        
        
        
        
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}

?>