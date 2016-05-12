<?php
    
//try and connect if not send error in response
try {
    
        session_start();
    
        $findDisplayAs = $_SESSION['DisplayAs'];
     
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $stmt = $conn->query("SELECT * FROM records WHERE inspector = '".$findDisplayAs."' ORDER BY dateadded DESC");
        
        echo "<tbody>";
        while($r = $stmt->fetch()) {
            //$rows[] = $r;  
                $inspector = $r["inspector"];
                $regHrs = $r["stHours"];
                $otHrs = $r["otHours"];
                $dtHrs = $r["dtHours"];
                $totalHrs = $r["hrsTotal"];
                $pID = $r["id"];
                $stat = $r["status"];
                $dtSub = date("m-d-Y", strtotime($r["date"]));
            
                echo '
                <tr data-bind="'.$pID.'" ><form id="upast" method="post" action="dashboard.php?id='.$uid.'&recid='.$pID.'" >
                    <td align="center">'.$dtSub.'</td>
                    <td align="center">'.$inspector.'</td>
                    <td align="center">'.$regHrs.'</td>
                    <td align="center">'.$otHrs.'</td>
                    <td align="center">'.$dtHrs.'</td>
                    <td align="center">'.$totalHrs.'</td>
                    <td align="center">';
                            if($stat == "submitted") { 
                                echo "<span style='color: green;'>".$stat."</span>"; 
                            } else if($stat == "re-submitted") { 
                                echo "<span style='color: #D97C04;'>".$stat."</span>"; 
                            } else if ($stat == "denied"){
	                            echo "<input type='submit' name='deniedbutt[]' class='redDenied' value='Entry Denied Click to Edit' /><input type='hidden' name='hiddenrecid[]' value='$pID' /></form>";
                            } else { echo "<span style='color: #1D5F9E;'>".$stat."</span>";  }
            echo '</td>
                </tr></form>';
        }
        echo "</tbody>"; 
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}

?>