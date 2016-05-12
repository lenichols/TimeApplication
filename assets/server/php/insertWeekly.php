<?php

ini_set("display_errors",1); 
error_reporting(E_ALL ^ E_NOTICE);
header('Access-Control-Allow-Origin: *');  

session_start();



if(!empty($_POST['sig'])){
	
	if(empty($POST['date'])){
		echo "<script>history.go(-1);</script>";
		//header('Location: ../../../dashboard/dashboard.php?id='.$_SESSION['uid'].'&nodata=true');
	}
	
	/*if(!empty($_POST['date'])){
		echo "You fill out the form";
		die();
	}*/

    //try and connect if not send error in response
    include_once("config.php");
    $findDisplayAs = $_SESSION['DisplayAs'];
    $usersRole = $_SESSION['role'];
    $empID = $_SESSION['empID'];

    try {

        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);

        $day = (!empty($_POST['date'])) ? $_POST['date'] : null;
        $regTime = (!empty($_POST['regTime'])) ? $_POST['regTime'] : null; //stTotal
        $timeOT = (!empty($_POST['timeOT'])) ? $_POST['timeOT'] : null;
        $timeDT = (!empty($_POST['timeDT'])) ? $_POST['timeDT'] : null;
        $task = (!empty($_POST['timeTask'])) ? $_POST['timeTask'] : null;
        $totalHrs = (!empty($_POST['totalHrs'])) ? $_POST['totalHrs'] : null; //hrsTotal
        //$client = (!empty($_POST['assignment'])) ? $_POST['assignment'] : null;
        $inspName = (!empty($_POST['inspName'])) ? $_POST['inspName'] : null;
        $emplID = (!empty($_POST['empID'])) ? $_POST['empID'] : null;
        
        //print_r($_POST['sig']);
        //die();
        $sig1 = (!empty($_POST['sig'])) ? $_POST['sig'] : null;
        $usersID = $_SESSION['uid'];
        $proj = (!empty($_POST['proj'])) ? $_POST['proj'] : null;
        $wr = (!empty($_POST['wr'])) ? str_replace(' ', '', $_POST['wr']) : null;
        $nts = (!empty($_POST['notes'])) ? $_POST['notes'] : null;
        $status = "submitted";
        $supervisorsnm = $_GET['sup'];
        $dynamRecID = mt_rand();

		if(isset($_POST['foundID'])){
			
				$c = (!empty($_POST['date'])) ? $_POST['date'] : null;
			
			} else {
				$c = array_filter($_POST['date']);
        }
        
        $sig = str_replace("data:image/png;base64,", "", $sig1);

        define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/tmp_sig/');
        $img = $_POST['sig'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = UPLOAD_DIR . $dynamRecID . '.png';
        $success = file_put_contents($file, $data);

		        try{
		        
		            //capture signature and insert in signatures table
		            $sigstmt = $conn->prepare('INSERT INTO signatures (refuuid, usersid, baseString) VALUES (?, ?, ?)');
		                $sigresult = $sigstmt->execute(array($dynamRecID, $usersID, $sig ));
		
		                    if($sigresult){

			                        if(isset($_POST['foundID'])){
				                        
				                        $foundRecID = $_POST['foundID'];
				                        
				                        $sth = $conn->prepare("UPDATE records SET date = ?, project = ?, wrnum = ?, hrsTotal = ?, otHours = ?, dtHours = ?, taskType = ?, stHours = ?, inspector = ?, notes = ?, status = ? WHERE id = ?");
										$sth->execute(array($c, $proj, $wr, $totalHrs, $timeOT, $timeDT, $task, $regTime, $inspName, $nts, 're-submitted',  $foundRecID));
		        
										header('Location: ../../../dashboard/dashboard.php?id='.$_SESSION['uid'].'&ressub=true');
										
										echo "Timesheet Re-Submitted!";
		        
							        } else {

					                         for($k=0;$k<=count($c)-1;$k++) {
					
					                            $stmt = $conn->prepare('INSERT INTO records (uuid, date, project, wrnum, hrsTotal, otHours, dtHours, taskType, stHours, inspector, eID, notes, status, supqueue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
					                            $result = $stmt->execute(array($dynamRecID, $c[$k], $proj[$k], $wr[$k], $totalHrs[$k], $timeOT[$k], $timeDT[$k], $task[$k], $regTime[$k], $inspName, $emplID, $nts[$k], $status, $supervisorsnm));
					
					                            if($result){
					                                
					                                  header('Location: ../../../dashboard/dashboard.php?id='.$_SESSION['uid'].'&sub=true');
					                                  
					                                  echo "Timesheet Submitted!";
					                                 
					
					                            } else {
					                                  echo "Record insert failed!";
					                            }
					
					                            print($result);
					                        }

					              }
		
		                    } else {
		                          echo "Dynamic ID generation failed! Go back and try again...";
		                    }
		             
		        } catch (Exception $e) {
		            echo $e->getMessage();
		        } 

    } catch(PDOException $e) {
          echo $e->getMessage();
    }
		
	
        
} else {
    echo "signature required!";
    header('Location: ../../../dashboard/dashboard.php?id='.$_SESSION['uid'].'&sig=false');
}
?>