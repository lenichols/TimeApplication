<?php
    
//try and connect if not send error in response
try {
        
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
        $stmt = $conn->query("SELECT * FROM users ORDER BY displayName ASC ");
        while($r = $stmt->fetch()) {
            //$rows[] = $r;  
                $idc = $r["id"];
                $inspector = $r["displayName"];
                $empCode = $r["eID"];
                $region = $r["region"];
                $st = $r["active"];
                $supr = $r["supName"];
                $roleUsr = $r["role"];
				$unm = $r["userName"];
            
                echo '
                <tr class="">
                    <td align="center"><input name="checkUids[]" id="c'.$idc.'" type="checkbox" value="'.$idc.'" class="checkboxReset"></td>
                    <td align="left">'.$inspector.'</td>
                    <td align="center">'.$roleUsr.'</td>
                    <td align="center">'.$empCode.'</td>
					<td align="center">'.$unm.'</td>
                    <td align="center">'.$region.'</td>
                    <td align="center">'.$supr.'</td>
                    <td align="center" width="10%">'; 
                    
                    if ($st == 1) { echo "<select name='employeeStatus[]' class='form-control empStatCheckbx'><option value='1'>Active</option>
                    <option value='0'>In-Active</option></select>";
                    } elseif ($st == 0) { echo "<select name='employeeStatus[]' class='form-control'><option value='0'>In-Active</option>
                    <option value='1'>Active</option></select>";
                    }
                    else { echo "<select name='employeeStatus[]' class='form-control'><option></option><option value='0'>In-Active</option>
                    <option value='1'>Active</option></select>";
                    }
                    
                echo '</td>
                </tr>';
        }

    } catch(PDOException $e) {
      echo $e->getMessage();
}

    
if(isset($_POST['updateUsers'])){
    
    if($_POST['checkUids']){

			    $upTheUsers = $_POST['updateUsers'];
			    $lineID = (!empty($_POST['checkUids'])) ? $_POST['checkUids'] : null; 
			    $eStat = (!empty($_POST['employeeStatus'])) ? $_POST['employeeStatus'] : null;
			
			    $b = array_filter($_POST['checkUids']);
			
			    for($t=0;$t<=count($b)-1;$t++) {
			
			        $pk = $conn->prepare('UPDATE users SET active = ? WHERE id = ?');
			        $j = $pk->execute(array($eStat[$t], $b[$t]));
			
			        if($j){
			              echo "<span style='color: green;'><i>Employee Updated!</i><br/></span>";
			        } else {
			              echo "<span style='color: red;'><i>Ooops! Something bad just happened on update...</i></span>";
			        }

    } //end of for
    echo "<meta http-equiv='refresh' content='0'>";
        
    } else {
        $message = "You must select a User to update. Check the checkbox to the left of the users name";
        echo "<script>alert(\"" . $message . "\"); window.history.back();</script>";
    }
}

if(isset($_POST['delUsers'])){
    
    if($_POST['checkUids']){

			        $delTheUsers = $_POST['delUsers'];
			        $lineID = (!empty($_POST['checkUids'])) ? $_POST['checkUids'] : null; 
			        $eStat = (!empty($_POST['employeeStatus'])) ? $_POST['employeeStatus'] : null;
			
			        $y = array_filter($lineID);
			
			        for($w=0;$w<=count($y)-1;$w++) {
			
			            $stmto = $conn->prepare('DELETE FROM users WHERE id = ?');
			            $s = $stmto->execute(array($y[$w]));
			
			            if($s){ echo "Post deleted!"; echo "<meta http-equiv='refresh' content='0'>"; }
			
			        } //end of for
    
    } else {
        
			        $message = "You must select a User to delete. Check the checkbox to the left of the users name";
			        echo "<script>alert(\"" . $message . "\"); window.history.back();</script>";
        
    }

}

?>