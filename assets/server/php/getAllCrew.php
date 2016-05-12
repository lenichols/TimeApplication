<?php

//try and connect if not send error in response
try {                      
        
        $supervisorsName = $_SESSION['DisplayAs'];
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $stmtSubmitted = $conn->prepare("SELECT displayName, userName, FROM_UNIXTIME(dateadded), active FROM users WHERE supName = :supName ORDER BY dateadded DESC");
        $stmtSubmitted->execute(array(":supName" => $supervisorsName));
        $howmanyfound = $stmtSubmitted->rowCount();
        
        echo '<tr colspan="4">Crew members found: <b>'.$howmanyfound.'</b></tr>';

        while($r = $stmtSubmitted->fetch(PDO::FETCH_ASSOC)) {
            
            	$dateTime = new DateTime($r["dateadded"], new DateTimeZone('America/Los_Angeles'));
				$time = $dateTime->format("m-d-Y");
                //$time = date( 'm-d-Y H:i:s', $r["timeadded"] );
                $disp = $r["displayName"];
                $unam = $r["userName"];
                $acti = $r["active"];

            if($howmanyfound > 0){
		                echo '
		                <tr>
		                    <td align="center" >'.$time.'</td>
		                    <td align="center">'.$disp.'</td>
		                    <td align="center">'.$unam.'</td>
		                    <td align="center">';
		                    if($acti == 1){
			                    echo "<span style='color: green;'>Active</span>";
		                    } else {
			                    echo "In-Active";
		                    }
		                echo '</td></tr>';
                
                } else {
                    echo '
                <tr class="">
                    <td colspan="4" align="center">No results found!</td>
                </tr>';
                }
        }
    
    } catch(PDOException $e) {
      echo $e->getMessage();
}

?>