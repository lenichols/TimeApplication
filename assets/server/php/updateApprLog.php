<?php

//echo "appr log found";
ini_set("display_errors",1); 
error_reporting(E_ALL ^ E_NOTICE);
header('Access-Control-Allow-Origin: *');  

session_start();



require_once dirname(__FILE__) . "/vendor/autoload.php";
use Jaspersoft\Client\Client;
include("./config.php");

include(SERVICES_INNER."/dashboard/Classes/fpdf.php");
include(SERVICES_INNER."/dashboard/Classes/fpdi.php");


try{
	
				$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);

                //$cboxID =  array_filter($_POST['selID']);
				$supNotes = (!empty($_POST['notes'])) ? $_POST['notes'] : null;
                $checkedID = array_filter($_POST['recCheckBx']);
                $recUnID = array_filter($_POST['selID']);
                $checkedDate = (!empty($_POST['selDate'])) ? $_POST['selDate'] : null;
				$findDisplayAs = $_SESSION['DisplayAs'];
				$foundRecs = count($_POST['recCheckBx']);
				$stupdate = 'approved';
				$flagSig = false;
                $flag=false;
                $flag3=false;
				$apprFlag = false;
		
	
				////STEP 1
				////post to approvallog table
				$stmt1 = $conn->prepare('INSERT INTO approvallog (ApprovalName, ApprovalNotes, ApprovalDate, ApprovalAction, QCount) values (?, ?, NOW(), ?, ?)');
				$apprResult = $stmt1->execute(array($findDisplayAs, $supNotes, 'approved', $foundRecs));
				//find last inserted id from previous insert
				$userid = $conn->lastInsertId();

				if($apprResult){
						$apprFlag = true;
				}
	
	
				////STEP 2
				if($apprFlag === true){
					
					//function stampSig($foundid){
					//if($flagSig = true){
						
					for($g=0;$g<=count($checkedID);$g++) {
										
						$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);

						//now update record with new logid stored in jasID column
						$stmtMark = $conn->prepare("UPDATE records SET jasperID = ?, status = ? WHERE id = ?");
						$stmtMark->execute(array($userid, $stupdate, $checkedID[$g]));

					}
						
					  	
					if(isset($_POST['noPDF'])){
							header('Location: ../../../dashboard/sup.php?auth='.$adminAuth.'&id='.$uid.'&apprNr=dn');
						} else {
						
					////STEP 5 /if no skip pdf checkbox is ticked/
					//function sendToJasperPDF($lastrecid){
							//$pdf = new FPDF();
							$name = $userid.'-timecard.pdf';
							$name2 = $userid.'-timecardx.pdf';
							$path = SERVICES_ROOT . 'pdf/' . $name;
							$path2 = SERVICES_ROOT . 'pdf/' . $name2;

							$image = './favicon.png';

								//now generate the pdf
								$controls = array("JasperID"=>$userid);
								$c = new Client("http://10.33.0.226:8080/jasperserver", "jasperadmin", "jasperadmin");
								$report = $c->reportService()->runReport('/reports/Live_Reports/TimeKeeper/SWG/SWG_Timecard', 'pdf', null, null, $controls);

								/*header('Cache-Control: must-revalidate');
								//header('Content-Type: application/octet-stream');
								header('Pragma: public');
								header('Content-Description: File Transfer');*/
								/*header('Content-Disposition: attachment; filename='.basename($path));*/
								/*header('Content-Transfer-Encoding: binary');
								header('Content-Length: ' . strlen($report));
								header('Content-Type: application/pdf');*/
								//
								if (file_put_contents($path, $report)){
									
											//if($stmtSigUpdt){
									
									
									for($h=0;$h<=count($checkedID)-1;$h++) {

										$stmtSigUpdt = $conn->prepare('UPDATE signatures SET jasperID = ? WHERE refuuid = ?');
										$sigRes = $stmtSigUpdt->execute(array($userid, $recUnID[$h]));

											if($sigRes){

												sleep(2);

												$pathOriginal = $path; 
												$pathSigned = $path2; 
												$image = SERVICES_ROOT.'tmp_sig/'.$recUnID[$h].'.png';

												$pdf = new FPDI();
												$pdf->AddPage();
												$pdf->setSourceFile(SERVICES_ROOT . 'pdf/'.$name); // Specify which file to import
												$tplidx2 = $pdf->importPage(1); // Import first page
												$pdf->Image($image,27,45,75,'PNG');

												$pdf->useTemplate($tplidx2, null, null, 0, 0, true); 
												//$pdf->Output($name2, 'F');
												$pdf->Output($name2, 'D');

											}


										}


									
								} ///end of file put contents


					} ///end if no pdf posted... else download pdf

								//} catch(PDOException $e) {
								//		 echo $e->getMessage();
								//}
					
					
					} else { 
							$flag=false;
							echo "error creating report";
					}

					
                
            
	
				
} catch(PDOException $e) {
		  echo $e->getMessage();
} 




?>