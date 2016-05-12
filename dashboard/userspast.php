<?php

session_start();
//$usersDisplayName = $_REQUEST['user'];
$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$adminAuth = $_SESSION['authenticated'];
$uid = $_SESSION['uid'];

if(empty($uid)){
    header('Location: ../killsession.php');
} 

include_once("../assets/server/php/config.php");
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
$getPgName = "Weekly Timesheet Entry Application";



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
    <script src="assets/js/jquery.tablesorter.js" type="text/javascript"></script>
    <script src="assets/js/jquery-ui.js" type="text/javascript"></script>
    <script src="assets/js/paging.js"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
	    	
	    	$("table").tablesorter({headers: {0: { sorter: false}}});
	    	//$("table").tablesorterPager({container: $("#pager")});
	    	
	    	$('table').paging({ limit: 10,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
			
			});

            $("select").on('change', function(h) {
                console.log($(this).val());
                $(this).closest('tr').find('[type=checkbox]').prop('checked', true);
            });

    	});
	</script>
    
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
                    <a href="./dashboard.php<?php echo '?id='.$uid; ?>">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-note2"></i>
                        <p>Past Weeks</p>
                    </a>
                </li>
                <li>
                    <a href="./userinfo.php<?php echo '?id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>My Info</p>
                    </a>
                </li>
                <!-- if users role is admin, show admin button -->
                <?php if($usersRole === "admin"){ ?>
                <li>
                    <a href="./admin.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i style="color: #21B6EC;" class="pe-7s-news-paper"></i>
                        <p style="color: #21B6EC;">Admin Panel</p>
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
                    <?php echo "Time for: <b>".$findDisplayAs."</b>"; ?>
                    <br/>
                    <h3>Your Submitted Time (All)</h3>
                    <div class="panel panel-default">
                      <div class="panel-body">
                         <form name="userschangeform" method="post">
                         <table class="main-table table table-bordered table-hover">
                              <thead>
                               <tr class="header">
                                  <th>Date</th>
                                  <th>Employee Name</th>
                                  <th>Reg(Hrs)</th>
                                  <th>OT(Hrs)</th>
                                  <th>DT(Hrs)</th>
                                  <th>Total Hours</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tfoot>
                                <tr class="total">
                                  <td align="left" colspan="7"></td>
                                </tr>

                              </tfoot>
                          
                            <?php include("../assets/server/php/getUsersRecordsAll.php"); ?>
                          
                          </table>
                          </form>
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

    
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/app.js"></script>
    
</html>
