<?php
    
//try and connect if not send error in response
try {
        $findDisplayAs = $_SESSION['DisplayAs'];
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
    
        if($_SESSION['authenticated'] != "sup"){
            $stmt = $conn->query("SELECT * FROM records WHERE status != 'approved' ORDER BY datamodified DESC");
        } 
    
        if($_SESSION['authenticated'] === "sup"){
            $stmt = $conn->query("SELECT * FROM records WHERE supqueue = '".$findDisplayAs."' ORDER BY dateadded DESC");
        }
        
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
				$expID = $r["ID_ExportLog"];
                $dtSub = date("m-d-Y", strtotime($r["date"]));
            
                echo '
                <tr class="">
                    <td align="center"><center><input type="checkbox" name="recCheckBx[]" class="checkboxReset" value="'.$pID.'"></center></td>
					<td align="center">'.$expID.'</td>
                    <td align="center">'.$dtSub.'</td>
                    <td align="center">'.$inspector.'</td>
                    <td align="center">'.$regHrs.'</td>
                    <td align="center">'.$otHrs.'</td>
                    <td align="center">'.$dtHrs.'</td>
                    <td align="center">'.$totalHrs.'</td>
                    <td align="center">';
                            if($stat == "approved") { 
                                echo "<span style='color: #1D5F9E;'><b>".$stat."</b></span>"; 
                            } elseif ($stat == "submitted"){
                                echo "<span style='color: #0D7E0B;'>".$stat."</span>"; 
                            } elseif ($stat == "re-submitted"){
                                echo "<span style='color: #E39608;'>".$stat."</span>"; 
                            } elseif ($stat == "denied"){
                                echo "<span style='color: orange;'>".$stat."</span>"; 
                            } else { echo "<span style='color: #333;'>".$stat."</span>";  }
            echo '</td>
                </tr>';
        }
        echo "</tbody>"; 
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}

?>