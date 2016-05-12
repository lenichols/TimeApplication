<?php

session_start();

$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$adminAuth = $_SESSION['authenticated'];
$uid = $_SESSION['uid'];

if($adminAuth != "admin"){
    header('Location: ../killsession.php');
} else {
    include_once("../assets/server/php/config.php");
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
    include("../assets/server/php/exportxls.php");
    $getPgName = "Administration Queue";
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
    <link href="assets/css/app.css" rel="stylesheet" />
    <link href="assets/css/fes.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/signature-pad.css">
    <script src="dist/sweetalert.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" >
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css' >
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="assets/js/jquery.tablesorter.js" type="text/javascript"></script>
    <script src="assets/js/jquery-ui.js" type="text/javascript"></script>
    <script src="assets/js/paging.js"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/signature_pad.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
			
			$("#exportAllnew").click(function(){
				
				swal({   
					title: "EXPORT",   
					text: "Export Selected Records? Records will be marked exported and removed from the main approved Queue.",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Export",   
					cancelButtonText: "Cancel",   
					closeOnConfirm: true,   
					closeOnCancel: true 
				}, function(isConfirm){   
					if (isConfirm) {     
						var form = $('#exportedTbl'); 
						form.submit();
						 
					} else {     
						swal("Cancelled", "You have cancelled this export.", "error");   
					} 
				});

			}); //approve clicked

	    	$("table").tablesorter({headers: {0: { sorter: false}, 8: { sorter: false}}});
	    	//$("table").tablesorterPager({container: $("#pager")});
	    	
	    	$('table').paging({ limit: 20,
		    	pageSize:10,
				size: 9999, // pick a number larger than your table
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
            
            //if employee added...set to true
	        <?php if(isset($_GET['empAdded'])){ ?>
                swal({   title: "Employee Added!",   text: "reloading page...",   timer: 2000,   showConfirmButton: false });
            <?php } ?>
            
            <?php if(isset($_GET['cliAdded'])){ ?>
                swal({   title: "Client Added!",   text: "reloading page...",   timer: 2000,   showConfirmButton: false });
            <?php } ?>
            
            $('#search').keyup(function()
                {
                    searchTable($(this).val());
                });
 

            function searchTable(inputVal)
            {
                var table = $('table');
                table.find('tbody tr').each(function(index, row)
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
                        
                        if(found == true) { 
	                        $(row).show(); 
	                        $(row).last().show(); 
	                    } else { 
		                    $(row).hide(); 
		                    //$(row).last().show(); 
		                }
                    }
                });
            }

          
          //$(document).on('focus', '[id=empName]', function(e) {
	      $('input#empName').change(function () {
		      
             if ($(this).val().length >  0) {
	             
                $(this).addClass( "requiredRed" );
                	$(this).prop("required", true);
                $('#empCode').addClass( "requiredRed" );
                	$('#empCode').prop("required", true);
                $('#empUN').addClass( "requiredRed" );
                	$('#empUN').prop("required", true);
                $('#supChosen').addClass( "requiredRed" );
                	$('#supChosen').prop("required", true);
             } else {
	             $(this).removeClass( "requiredRed" );
                	$(this).prop("required", false);
                $('#empCode').removeClass( "requiredRed" );
                	$('#empCode').prop("required", false);
                $('#empUN').removeClass( "requiredRed" );
                	$('#empUN').prop("required", false);
                $('#supChosen').removeClass( "requiredRed" );
                	$('#supChosen').prop("required", false);
             }
         });
            
    	});
	</script>
    <style>
		.form-horizontal .form-group {
		    width: 60% !important;
		}
        .bluecolor {
            color: #3E6C99 !important;
        }
        .requiredRed {
            outline: none !important;
            border:1px solid red;
            box-shadow: 0 0 10px #719ECE;
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
                    <a href="#">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="./past.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>Past Weeks</p>
                    </a>
                </li>
                <li>
                    <a href="./users.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                <!-- if users role is admin, show admin button -->
                <?php if($adminAuth === "admin"){ ?>
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-news-paper"></i>
                        <p>Admin Panel</p>
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
                    <?php echo "Administrator: <b>".$findDisplayAs."</b>"; ?>
                    <br/>
                    <h3>Approved Time Entries<span class="pull-right"><input type="reset" value="Refresh Table" class="btn-med btn" style="width: 100%;margin: 0 78px 5px 0;" onClick="window.location.reload()"></span></h3>
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
	                      <!--<label> Show more:&nbsp;
	                      <select id='selectedRecs' class='filTime pagesize'>
		                      <option value="10">10</option>
		                      <option value="20">20</option>
		                      <option value="40">40</option>
		                      <option value="100">100</option>
	                      </select>
	                      </label>-->
                         <form id="exportedTbl" method="post" action="../assets/server/php/updateExpLog.php">
                         <table class="main-table table table-bordered table-hover tablesorter">
                          <thead>
                            <tr class="header">
                              <th width="10%"></th>
                              <th>Date</th>
                              <th>Employee Name</th>
                              <th>Reg(Hrs)</th>
                              <th>OT(Hrs)</th>
                              <th>DT(Hrs)</th>
                              <th>Total Hours</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr class="total">
                              <td align="left" colspan="2">
                                  <input id="allCheckBoxes" type="checkbox">*Export All
                              &nbsp;&nbsp;<input name="export" type="reset" class="btn-small btn" value="Clear"></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td colspan="2" align="right"><input id="exportAllnew" name="export" type="button" class="btn-small colorBlueDKBtn btn" value="Export to .xls"></td>
                             
                            </tr>
                            
                          </tfoot>
                          <tbody>
                            <?php include( dirname(__FILE__) ."/../assets/server/php/getRecordsNew.php"); ?>
                          </tbody>
                      </table>
                      </div>
                    </div>
                    </form>
                    
                    <!-- export employee panel -->
                    <h3>Add Client</h3>
                    <div class="panel panel-default blueGra">
                        <div class="panel-body">
                          <form  action="" method="post" onsubmit="return confirm('Add Client?');">
                          <div class="col-sm-3">
                                <input id="addClientName" name="addClientName" type="text" class="form-control" placeholder="Enter a Client Name">
                          </div>
                          <div class="bluecolor col-sm-3">
                              <br/>
                                <input id="clientPDFReg" name="clientPDFReg" type="checkbox" style="width: 25px !important;" value="1">Requires PDF Confirmation?
                          </div>
                          <div class="col-md-1">
                                <input name="addClient" type="submit" class="btn-small colorBlueDKBtn btn" value="Add">
                          </div>
                          </form>
                          <form id="clientBox"  name="clientBox" method="post" onsubmit="return confirm('Delete Client?');">
                          <div class="col-md-12 pull-left" style="margin-top: 5px;">
                                <?php include( dirname(__FILE__) ."/../assets/server/php/addClient.php"); ?>
                          </div>  
                          </form>
                                <?php include( dirname(__FILE__) ."/../assets/server/php/delClient.php"); ?>
                          </div>
                          
                        </div>
                    </div>

                    <h3>Add Employee</h3>
                    <div class="panel panel-default">
                        <div id="empWrapper" class="panel-body">
                          <div class="col-md-12">
                            Enter an <b>Employee Name</b>, <b>Employee Code</b>, <b>Supervisor</b>  and <b>Employee Username</b>. *Standard <i>FES password</i> will be added once created giving the employee the option to change their password from their control panel.<hr/>
                          </div>
                          <form action="" method="post" >
                          <div class="bluecolor col-md-4">
                              Employee's First &amp; Last Name<br/>
                              <input id="empName" name="empName" type="text" class="form-control" placeholder="Ralpha Clemente">
                          </div>
                          <div class="bluecolor col-md-2">
                              Employee's ID#<br/>
                              <input id="empCode" name="empCode" type="text" class="form-control" placeholder="2990-A726898">
                          </div>
                          <div class="bluecolor col-md-2">
                              Username: flast<br/>
                              <input id="empUN" name="empUN" type="text" class="form-control" placeholder="rclemente">
                          </div>
                          <div class="bluecolor col-md-2">
                              Sup. Assigned<br/>
                              <select class="form-control" name="supChosen" id="supChosen">
                                  
                                  <option value="" disabled selected>choose</option>
                                  <?php include( dirname(__FILE__) ."/../assets/server/php/getSupervisors.php"); ?>
                              </select>
                          </div>
                          <div class="col-md-2">
                              <br/>
                                <input name="addEmployee" id="addEmp" type="submit" class="btn-small colorBlueDKBtn btn" value="Add">
                          </div>
                          </form>
                          <div class="col-md-12">
                            <?php include( dirname(__FILE__) ."/../assets/server/php/addEmployee.php"); ?>
                          </div>
                        </div>
                    </div>  
            </div>
        </div>
        

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

    <script src="assets/lib/moment.min.js" data-main="assets/src/main"></script>
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <!--  Notifications Plugin    -->
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/app.js"></script>
	
    <script src="assets/lib/require-jquery.js" data-main="assets/src/main"></script>
    
</html>
