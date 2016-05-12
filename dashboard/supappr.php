<?php
ini_set("display_errors",1); 
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');  
error_reporting(E_ERROR | E_PARSE);
session_start();

$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$adminAuth = $_SESSION['authenticated'];
$uid = $_SESSION['uid'];

require_once dirname(__FILE__) . "/vendor/autoload.php";
use Jaspersoft\Client\Client;

if($adminAuth == "admin" || $adminAuth == "sup"){
     include_once("../assets/server/php/config.php");
     include(dirname(__FILE__)."/Classes/fpdf.php");
     include(dirname(__FILE__)."/Classes/fpdi.php");

     $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
     $getPgName = "Supervisors Approved Timesheet Entries";
    //print $_SERVER['DOCUMENT_ROOT'];
    if(isset($_POST['pulledRefID'])){
       
        $name = $_POST['jasID'].'-timecard.pdf';
        $name2 = $_POST['jasID'].'-timecardx.pdf';
        $path = '../pdf/' . $name; 
        $path2 = '../pdf/' . $name2; 
        $foundUU = $_POST['RefID'];
        $foundJasID = $_POST['jasID'];
    
        $image = '../tmp_sig/'.$foundUU.'.png';
        
        if (file_exists($path2)) {
            
            $pdf = new FPDI();
            $pdf->Output($path2, 'F');
            
        } else {
	        
	        		$controls = array("JasperID"=>$foundJasID);
                    $c = new Client("http://10.33.0.226:8080/jasperserver", "jasperadmin", "jasperadmin");
                    $report = $c->reportService()->runReport('/reports/Live_Reports/TimeKeeper/SWG/SWG_Timecard', 'pdf', null, null, $controls);
                    //
                    if (file_put_contents($path, $report)){
			            
			                $pdf = new FPDI();
			                $pdf->AddPage();
			                $pdf->setSourceFile(dirname(__FILE__).'/../pdf/'.$name); // Specify which file to import
			                $tplidx2 = $pdf->importPage(1); // Import first page
			                $pdf->Image($image,27,45,75,'PNG');
			
			                $pdf->useTemplate($tplidx2, null, null, 0, 0, true); 
			                //$pdf->Output($name2, 'F');
			                $pdf->Output($name2, 'D');
			        }
        }

    }

     
    
} else {
   header('Location: ../killsession.php');
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
    <link href="assets/css/blue-style.css" rel="stylesheet" />

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
	    	
	    	$("table").tablesorter({headers: {0: { sorter: false}, 8: { sorter: false}}});
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
                <li>
                    <a href="./sup.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
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
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-note2"></i>
                        <p>Approved Time</p>
                    </a>
                </li>
                <!-- if users role is admin, show admin button -->
                <?php if($adminAuth === "sup"){ ?>
                <li>
                    <a href="./sup.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-news-paper"></i>
                        <p>Supervisors Panel</p>
                    </a>
                </li>
                <?php } ?>
                <!-- end of show admin button -->
            </ul>
    	</div>
    </div>
    <div class="main-panel">
        <!-- topNav -->
        <?php include( dirname(__FILE__) ."/partials/topNav.php"); ?>
        
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <?php echo "Supervisor: <b>".$findDisplayAs."</b>"; ?>
                    <br/>
                    <h3>All Approved Time (PDF)<span class="pull-right"><input type="reset" value="Refresh Table" class="btn-med btn" style="width: 100%;margin: 0 78px 5px 0;" onClick="window.location.reload()"></span></h3>
                    <small style="color: #0e4880;">*Displaying time entries (Newest to Oldest)</small>
                    
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
                        
                         <form method="post">
                         <table class="main-table table table-bordered table-hover tablesorter">
                          <thead>
                            <tr class="header">
                              <th>Beg Date</th>
                              <th>Employee Name</th>
                              <th>WR</th>
                              <th>Reg(Hrs)</th>
                              <th>OT(Hrs)</th>
                              <th>DT(Hrs)</th>
                              <th>Total Hours</th>
                              <th>Status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr class="total">
                              <td align="left" colspan="2">
                                  <!--<input id="allCheckBoxes" type="checkbox" style="width: 25px !important;">*Select All
                              &nbsp;&nbsp;<input name="approveAll" type="reset" class="btn-small btn" value="Clear">--></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td colspan="3" align="right"><!--<input id="exportAllnew" name="export" type="submit" class="btn-small colorBlueDKBtn btn" value="Approve Selected">&nbsp;&nbsp;<input id="denySelected" name="denySelected" type="submit" class="btn-small colorRedBtn btn" value="Deny Selected">--></td>
                             
                            </tr>
                            
                          </tfoot>
                          <tbody>
                            <?php include( dirname(__FILE__) ."/../assets/server/php/getAllSupApproved.php"); ?>
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
        
        $(document).ready(function(){
            
        $('#search').keyup(function()
                {
                    searchTable($(this).val());
                });
 
            function searchTable(inputVal)
            {
                var table = $('table');
                table.find('tr').each(function(index, row)
                {
                    var allCells = $(row).find('td');
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
    
    <script src="assets/lib/moment.min.js" data-main="assets/src/main"></script>
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <!--  Notifications Plugin    -->
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/app.js"></script>
    <script src="assets/lib/require-jquery.js" data-main="assets/src/main"></script>
    
</html>
