<?php

//try and connect if not send error in response
try {
        ///this table shows all approved time from sup queue - displayed on admin
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $stmt = $conn->query("SELECT * FROM records WHERE status = 'approved' ORDER BY dateadded DESC");
        while($r = $stmt->fetch()) {
	        
                $inspector = $r["inspector"];
                $regHrs = $r["stHours"];
                $otHrs = $r["otHours"];
                $dtHrs = $r["dtHours"];
                $totalHrs = $r["hrsTotal"];
                $pID = $r["id"];
                $dtSub = date("m-d-Y", strtotime($r["date"]));
                echo '
                <tr class="">
                    <td align="center"><input type="checkbox" name="recCheckBx[]" value="'.$pID.'" class="cbox" ></td>
                    <td align="center">'.$dtSub.'</td>
                    <td align="center">'.$inspector.'</td>
                    <td align="center">'.$regHrs.'</td>
                    <td align="center">'.$otHrs.'</td>
                    <td align="center">'.$dtHrs.'</td>
                    <td align="center">'.$totalHrs.'</td>
                </tr>';
        }
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}



?>