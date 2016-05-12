<?php
ini_set("display_errors",1); 
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');  

session_start();

$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$adminAuth = $_SESSION['authenticated'];
$uid = $_SESSION['uid'];
$randomJasID = "";
//$randomJasID = generate_();
//echo $randomJasID;
//if approved button is clicked
require_once dirname(__FILE__) . "/vendor/autoload.php";
use Jaspersoft\Client\Client;

if($adminAuth == "admin" || $adminAuth == "sup"){
     include_once("../assets/server/php/config.php");
     include(dirname(__FILE__)."/Classes/fpdf.php");
     include(dirname(__FILE__)."/Classes/fpdi.php");
     include(dirname(__FILE__)."/Classes/uuid.php");
     
     $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
     $getPgName = "Supervisors Approval Panel";
    
} else {
   header('Location: ../killsession.php');
}








if(isset($_POST['approvedSelected'])){
		//$randomJasID = mt_rand(10000000, 99999999);
		$randomJasID = generate_uuid();
	
	?>

<script>
	swal({   
		title: "Comments",   
		text: "(Optional) Add A Note to this Approval.",   
		type: "input",   
		showCancelButton: true,   
		closeOnConfirm: false,   
		animation: "slide-from-top",   
		inputPlaceholder: "Write something" 
	}, function(inputValue){   
		
		if (inputValue === false) { return false; } else {
			localStorage.setItem("recNotes", inputValue);
			swal("Nice!", "You wrote: " + inputValue, "success"); 
		}
	});
</script>

<?php
	

        try{

                //$cboxID =  array_filter($_POST['selID']);
                $checkedID = array_filter($_POST['recCheckBx']);
                $recUnID = array_filter($_POST['selID']);
                $checkedDate = (!empty($_POST['selDate'])) ? $_POST['selDate'] : null;
                $flag=false;
                $flag3=false;
                //recCheckBx
                for($g=0;$g<=count($checkedID)-1;$g++) {
					
					$stmtMark = $conn->prepare('INSERT INTO approvallog (ApprovalName,ApprovalNotes,ApprovalDate,ApprovalAction) values (?, ?, Now(), ?); select LAST_INSERT_ID();');
                    $jasperRes = $stmtMark->execute(array($findDisplayAs, $addedNotes, NOW(), 'approved'));

                        if($jasperRes){
	                        if(isset($_POST['noPDF'])){
		                        header('Location: ?auth='.$adminAuth.'&id='.$uid.'&apprNr=dn');
	                        } else {
                            	$flag=true;
                            }
                        } else {
                              //echo "Timesheet creation failed!";
                        }
					
					
					/*
                    $stmtMark = $conn->prepare('UPDATE records SET jasperID = ?, status = "approved" WHERE id = ?');
                    $jasperRes = $stmtMark->execute(array($randomJasID, $checkedID[$g]));

                        if($jasperRes){
	                        if(isset($_POST['noPDF'])){
		                        header('Location: ?auth='.$adminAuth.'&id='.$uid.'&apprNr=dn');
	                        } else {
                            	$flag=true;
                            }
                        } else {
                              //echo "Timesheet creation failed!";
                        } 
					*/

                 }
            
                if($flag==true) {
                    
                    //$pdf = new FPDF();

                    $name = $randomJasID.'-timecard.pdf';
                    $name2 = $randomJasID.'-timecardx.pdf';
                    $path = '../pdf/' . $name;
                    $path2 = '../pdf/' . $name2; 
                    
                    $image = './favicon.png';
                    //$pdf = new FPDI();

                    //now generate the pdf
                    $controls = array("JasperID"=>$randomJasID);
                    $c = new Client("http://10.33.0.226:8080/jasperserver", "jasperadmin", "jasperadmin");
                    //$report = $c->reportService()->runReport('/reports/Live_Reports/TimeKeeper/SWG/SWG_Timecard', 'pdf', null, null, $controls);
					$report = $c->reportService()->runReport('/reports/Test/TimeKeeper/SWG_Timecard', 'pdf', null, null, $controls);

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

                        //mark record with accepted flag and remove from submitted table
                        for($h=0;$h<=count($checkedID)-1;$h++) {

                            $stmtSigUpdt = $conn->prepare('UPDATE signatures SET jasperID = ? WHERE refuuid = ?');
                            $sigRes = $stmtSigUpdt->execute(array($randomJasID, $recUnID[$h]));

                                if($sigRes){
                                    
                                    sleep(2);
                                    
                                            $pathOriginal = $path; 
                                            $pathSigned = $path2; 
                                            //$foundUU = $_POST['RefID'];

                                            $image = '../tmp_sig/'.$recUnID[$h].'.png';
                                            //$image = '../favicon.png';

                                            if (file_exists($path2)) {

                                                $pdf = new FPDI();
                                                $pdf->Output($path2, 'F');

                                            } else {

                                                    $pdf = new FPDI();
                                                    $pdf->AddPage();
                                                    $pdf->setSourceFile(dirname(__FILE__).'/../pdf/'.$name); // Specify which file to import
                                                    $tplidx2 = $pdf->importPage(1); // Import first page
                                                    $pdf->Image($image,27,45,75,'PNG');

                                                    $pdf->useTemplate($tplidx2, null, null, 0, 0, true); 
                                                    //$pdf->Output($name2, 'F');
                                                    $pdf->Output($name2, 'D');

                                            }

                                } else {
                                      echo "Accepted flag could not be updated!";
                                }
                        }

                    }
                    
                  } else { 
                        $flag=false;
                        echo "error creating report";
                }
            

        } catch(PDOException $e) {
                  echo $e->getMessage();
        } 
    
}


if(isset($_POST['denySelected'])){
    
    $cboxdy = array_filter($_POST['recCheckBx']);
    for($b=0;$b<=count($cboxdy)-1;$b++) {
        $stmtAppr = $conn->prepare('UPDATE records SET status = "denied" WHERE id = ?');
        $jasperResDy = $stmtAppr->execute(array($cboxdy[$b]));
            if($jasperResDy){
	            
	            header('Location: ?auth='.$adminAuth.'&id='.$uid.'&deniedDone=true');

            } else {
                  echo "Deny flag could not be updated!";
            }
    }
}


if(isset($_POST['deleteSelected'])){
    $cboxd = array_filter($_POST['recCheckBx']);
    for($y=0;$y<=count($cboxd)-1;$y++) {
        $stmtDel = $conn->prepare('UPDATE records SET status = "deleted" WHERE id = ?');
        $jasperResDel = $stmtDel->execute(array($cboxd[$y]));
            if($jasperResDel){
	            
	            header('Location: ?auth='.$adminAuth.'&id='.$uid.'&deleteDone=true');

            } else {
                  echo "Deleted flag could not be updated!";
            }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favico.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>FES TimeKeeper App</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="viewport" content="width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/bootstrap-datetimepicker.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <link href="assets/css/app.css" rel="stylesheet"/>
    <link href="assets/css/fes.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/signature-pad.css">

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link href="assets/css/blue-style.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="assets/js/jquery-ui.js" type="text/javascript"></script>
    <script src="assets/js/paging.js"></script>
    <script src="assets/js/jquery.tablesorter.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/signature_pad.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
	    	
	    	$("table").tablesorter({headers: {0: { sorter: false}}});
	    	//$("table").tablesorterPager({container: $("#pager")});
	    	
	    	$('table').paging({ limit: 20,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
			
			});
         
            $("#allCheckBoxes").on('change', function(e){
                 var table= $(e.target).closest('table');
                 $("td input:checkbox",table).prop('checked', this.checked); 
            });
            
            $(".checkboxReset").on('change', function(){
                console.log("client checked");
                document.clientBox.submit(); 
            });
            
            $('#search').keyup(function()
                {
                    searchTable($(this).val());
                });
 

            function searchTable(inputVal)
            {
                var table = $('table');
                table.find('tr').each(function(index, row)
                {
                    var allCells = $(row).find('tbody td');
                    if(allCells.length > 0)
                    {
                        var found = false;
                        allCells.each(function(index, td)
                        {
                            var regExp = new RegExp(inputVal, 'i');
                            if(regExp.test($(td).text()))
                            {
                                found = true;
                                return false;
                            }
                        });
                        if(found == true)$(row).show();else $(row).hide();
                    }
                });
            }
    	});
	</script>
	<style>
		.form-horizontal .form-group {
		    width: 60% !important;
		}
	</style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="blue" data-image="assets/img/sidebar-5.jpg">

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="http://www.frontline-energy.com" class="simple-text">
                    <img src="../assets/img/festimekeeperwt-big.png" style="width: 75%;" />
                </a>
            </div>

            <ul class="nav">
                <?php if($adminAuth === "sup"){ ?>
                <li>
                    <a href="./sup.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php } ?>
                <li>
                    <a href="./crew.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-news-paper"></i>
                        <p>Assigned Crew</p>
                    </a>
                </li>
                <li>
                    <a href="./past.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>Past Timesheets</p>
                    </a>
                </li>
                <li>
                    <a href="./supappr.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>Approved Time</p>
                    </a>
                </li>
                <!-- if users role is admin, show admin button -->
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-news-paper"></i>
                        <p>Supervisors Panel</p>
                    </a>
                </li>
                <!-- end of show admin button -->
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <!--topNav -->
        <?php include( dirname(__FILE__) ."/partials/topNav.php"); ?>
        
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <?php echo "Supervisor: <b>".$findDisplayAs."</b>"; ?>
                    <br/>
                    <h3>Submitted Time<span class="pull-right"><input type="reset" value="Refresh Table" class="btn-med btn" style="width: 100%;margin: 0 78px 5px 0;" onClick="window.location.reload()"></span></h3>
                    <?php if(isset($_GET['appr']) == true) {
	                    	sleep(1);
                            echo "<span style='color: green;'><i>Report Generated for Approved Time. Go to 'Approved Timesheets' to see the compiled Timesheet.</i></span>";
                        }
                        if(isset($_GET['apprNr']) == true) {
	                        sleep(1);
                            echo "<span style='color: green;'><i>Records marked approved!</i></span>";
                        }
                        
                        if(isset($_GET['deniedDone']) == true) {
	                        sleep(1);
                            echo "<span style='color: red;'><i>Records marked denied!</i></span>";
                        }
                        
                        if(isset($_GET['deleteDone']) == true) {
	                        sleep(1);
                            echo "<span style='color: red;'><i>Records marked deleted! Users can not edit deleted records.</i></span>";
                        }
                    ?>
                    <div class="panel panel-default">
                      <div class="panel-body">
                          <form class="form-horizontal" role="form">
                              <div class="form-group pull-right">
                                <label for="input1" class="col-lg-6 control-label" style="font-size: 16px;">Search Time Entries:</label>
                                <div class="col-lg-6">
                                  <input type="text" id="search" class="form-control" style="" placeholder="search..."></input>
                                </div>
                              </div>
                          </form> 
                        
                         <form method="post" action="">
                         <table class="main-table table table-bordered table-hover tablesorter">
                          <thead>
                            <tr class="header">
                              <th data-sorter="false"></th>
                              <th width="10%">Date</th>
                              <th>Employee Name</th>
                              <th>WR</th>
                              <th>Client</th>
                              <th>Task</th>
                              <th>Reg(Hrs)</th>
                              <th>OT(Hrs)</th>
                              <th>DT(Hrs)</th>
                              <th>Total Hours</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr class="total">
                              <td align="left" colspan="2">
                                  <input id="allCheckBoxes" type="checkbox" style="width: 25px !important;">*Select All
                              &nbsp;&nbsp;<input name="approveAll" type="reset" class="btn-small btn" value="Clear"></td>
                              <td colspan="9" align="right"><input id="noPDF" name="noPDF" type="checkbox" class="btn-small colorBlueDKBtn btn">&nbsp;<span style='font-size: 10px;'>Skip PDF Export</span>&nbsp;&nbsp;<input id="approvedSelected" name="approvedSelected" type="submit" class="btn-small colorBlueDKBtn btn" value="Approve Selected">&nbsp;&nbsp;<input id="denySelected" name="denySelected" type="submit" class="btn-small colorYellowBtn btn" value="Deny Selected">&nbsp;&nbsp;<input id="deleteSelected" name="deleteSelected" type="submit" class="btn-small colorRedBtn btn" value="Delete Selected"></td>
                             
                            </tr>
                            
                          </tfoot>
                          <tbody>
                            <?php include( dirname(__FILE__) ."/../assets/server/php/getSubmittedRecordsNew.php"); ?>
                          </tbody>
                      </table>
                      </div>
                    </div>
                    </form>
        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                            <a href="#">
                                Help
                            </a>
                        </li>
                        <li>
                            <a href="../killsession.php">
                                Log Out
                            </a>
                        </li>
                    </ul>
                </nav>
                <p class="copyright pull-right">
                    &copy;2016-2017 <a href="mailto: lashauna.nichols@g2-is.com">Engineering Team at:</a> G2-IS/Frontline Energy Services
                </p>
            </div>
        </footer>

    </div>
</div>
</body>
    <script>
        //look for status messages and color them as appropriate
        $('table tr td').each(function(){
              var texto = $(".foundStatus").html();
              if(texto === 'submitted'){
                  $(".foundStatus").addClass( "blueSub" );
              }
            
             if(texto === 'resubmitted'){
                  $(".foundStatus").addClass( "greenSub" );
              }
        });
    </script>
    <script src="assets/lib/moment.min.js" data-main="assets/src/main"></script>
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <!--  Notifications Plugin    -->
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/app.js"></script>
    <script src="assets/lib/require-jquery.js" data-main="assets/src/main"></script>
</html>
