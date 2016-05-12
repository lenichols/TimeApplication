<?php

//try and connect if not send error in response
try {

        $supervisorsName = $_SESSION['DisplayAs'];
        
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $stmtSubmitted = $conn->prepare("SELECT * FROM records WHERE supqueue = :supName AND status = 'approved' GROUP BY jasperID ORDER BY inspector, date DESC");
        $stmtSubmitted->execute(array(":supName" => $supervisorsName));
        $howmanyfound = $stmtSubmitted->rowCount();

        while($r = $stmtSubmitted->fetch(PDO::FETCH_ASSOC)) {
            //$rows[] = $r;  
            
                $inspector = $r["inspector"];
                $regHrs = $r["stHours"];
                $otHrs = $r["otHours"];
                $dtHrs = $r["dtHours"];
                $totalHrs = $r["hrsTotal"];
                $pID = $r["id"];
                $stateStatus = $r["status"];
                $jaspID = $r["jasperID"];
                $refuuid = $r["uuid"];
                $wr = $r["wrnum"];
                $jid = $r["jasperID"];
                $dtSub = date("m-d-Y", strtotime($r["date"]));
                //echo $refuuid;

            if($howmanyfound > 0){
                echo '
                <tr>
                    <td align="center" >'.$dtSub.'</td>
                    <td align="center"><b>'.$inspector.'</b></td>
                    <td align="center">'.$wr.'</td>
                    <td align="center">'.$regHrs.'</td>
                    <td align="center">'.$otHrs.'</td>
                    <td align="center">'.$dtHrs.'</td>
                    <td align="center">'.$totalHrs.'</td>
                    <td align="center" style="color: #1F5E99;" ><b>'.$stateStatus.'</b></td></a>
                    <td align="center"><form name="findPDF" method="post">'; 
                    if($jaspID != null){ echo '<button name="pulledRefID" value="'.$refuuid.'" type="submit">View</button>
	                    	<input type="hidden" name="jasID" value="'.$jaspID.'" />';
	                }
	                    
	                echo '<input type="hidden" name="RefID" value="'.$refuuid.'" /></form></td>
                </tr>';
                } else {
                    echo '
                <tr class="">
                    <td colspan="8" align="center">No results found!</td>
                </tr>';
                }
        }

    } catch(PDOException $e) {
      echo $e->getMessage();
}





?>