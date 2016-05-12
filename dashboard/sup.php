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
//require_once dirname(__FILE__) . "/vendor/autoload.php";
//use Jaspersoft\Client\Client;

if($adminAuth == "admin" || $adminAuth == "sup"){
	
	 include_once("../assets/server/php/config.php");
     //
     
     
     $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
     $getPgName = "Supervisors Approval Panel";
    
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
	<script src="dist/sweetalert.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">
    <script type="text/javascript">
    	$(document).ready(function(){
	
			$("#approvedSelected").click(function(){
				swal({   
					title: "ADD NOTE",   
					text: "Add A Note to this Approval (Optional)",   
					type: "input",   
					showCancelButton: true,   
					closeOnConfirm: true,   
					animation: "slide-from-top",   
					inputPlaceholder: "Write something" 
				}, function(inputValue){   

					if (inputValue === false) { 
						return false; 
					} else {
						
							var form = $('#subRecords'); // You need to use standart javascript object here
							$('form#subRecords').append('<input type="hidden" name="notes" value="'+inputValue+'" /> ');
							form.submit();

							/*
							 $.ajax({
								url: approveURL,
								data: formData,
								contentType: false,
								processData: false,
								type: 'POST',
								success: function(data){
									//if(data === "complete"){
										//console.log(data);
										//console.log("this is input val: " + inputValue);
										//swal("Confirmed", "Timesheet Created!"); 
										/*swal({   
											title: "Complete",
											text: "<span style='color:#F8BB86'>Timecard Generated<span>",   
											html: true 
										});*/
									//}
							/* 	}
							}); */
						
					}
				});

			}); //approve clicked
	    	
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
<?php //echo $_SERVER["DOCUMENT_ROOT"]; ?>
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
                        
                         <form id="subRecords" method="post" action="../assets/server/php/updateApprLog.php">
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
                              <td colspan="9" align="right"><input id="noPDF" name="noPDF" type="checkbox" class="btn-small colorBlueDKBtn btn">&nbsp;<span style='font-size: 10px;'>Skip PDF Export</span>&nbsp;&nbsp;<input id="approvedSelected" name="approvedSelected" type="button" class="btn-small colorBlueDKBtn btn" value="Approve Selected">&nbsp;&nbsp;<input id="denySelected" name="denySelected" type="submit" class="btn-small colorYellowBtn btn" value="Deny Selected">&nbsp;&nbsp;<input id="deleteSelected" name="deleteSelected" type="submit" class="btn-small colorRedBtn btn" value="Delete Selected"></td>
                             
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
