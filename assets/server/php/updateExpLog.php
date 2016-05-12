<?php

//echo "appr log found";
ini_set("display_errors",1); 
error_reporting(E_ALL ^ E_NOTICE);
header('Access-Control-Allow-Origin: *');  

session_start();

require_once dirname(__FILE__) . "/vendor/autoload.php";
use Jaspersoft\Client\Client;
include("./config.php");


try{
	
				$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
				$findDisplayAs = $_SESSION['DisplayAs'];
				$foundRecs = count($_POST['recCheckBx']);
				$lnID = (!empty($_POST['recCheckBx'])) ? $_POST['recCheckBx'] : null; 
        		$arrW = array_filter($lnID);
	
				$stupdate = 'exported';
				$expFlag = false;
		
	
				////STEP 1-insert into export log exported info
				$stmt1 = $conn->prepare('INSERT INTO exportlog (ExportUserName, ExportNotes, ExportTimeStamp, QCount) values (?, ?, NOW(), ?)');
				$expResult = $stmt1->execute(array($findDisplayAs, null, $foundRecs));
				//find last inserted id from previous insert
				$userid = $conn->lastInsertId();

				if($expResult){
					
						$lnID = (!empty($_POST['recCheckBx'])) ? $_POST['recCheckBx'] : null; 
						$arrW = array_filter($lnID);


						///////loop through records tbl and set the status to exported					
						for($z=0;$z<=count($arrW)-1;$z++) {

							//now update record with new logid stored in jasID column
							$stmtMark = $conn->prepare("UPDATE records SET ID_ExportLog = ?, status = ? WHERE id = ?");
							$stmtMark->execute(array($userid, $stupdate, $arrW[$z]));

						}
					
						$expFlag = true;
				}
	
	
				////STEP 2
				if($expFlag === true){

						//now generate the pdf
						$controls = array("ID_ExportLog"=>$userid);
						$c = new Client("http://10.33.0.226:8080/jasperserver", "jasperadmin", "jasperadmin");
						$report = $c->reportService()->runReport('/reports/Live_Reports/TimeKeeper/Output_For_Billing/Alert_Export', 'xls', null, null, $controls);

						header('Cache-Control: must-revalidate');
						//header('Content-Type: application/octet-stream');
						header('Pragma: public');
						header('Content-Description: File Transfer');
						header('Content-Disposition: attachment; filename=time-export.xls');
						header('Content-Transfer-Encoding: binary');
						header('Content-Length: ' . strlen($report));
						header('Content-Type: application/vnd.ms-excel');
						//
						echo $report;

				}
            
	
				
} catch(PDOException $e) {
		  echo $e->getMessage();
} 




?>