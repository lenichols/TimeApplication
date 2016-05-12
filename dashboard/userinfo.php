<?php

session_start();
//$usersDisplayName = $_REQUEST['user'];
$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
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
    <script src="dist/sweetalert.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
    	$(document).ready(function(){

            $("select").on('change', function(h) {
                console.log($(this).val());
                $(this).closest('tr').find('[type=checkbox]').prop('checked', true);
            });
            
            function passwordChangeSuccess(){
                swal("Password Change was successful!")
            }

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
                <li>
                    <a href="./userspast.php<?php echo '?id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>Past Weeks</p>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-note2"></i>
                        <p>My Info</p>
                    </a>
                </li>
    
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
                    <h3>Update/Change Password (My Info)</h3>
                    <?php include( dirname(__FILE__) ."/../assets/server/php/changePass.php"); ?>
                    <div class="panel panel-default">
                      <div class="panel-body">
                          <div class="col-md-6">
                            <form method="post" action="" >
                              <div class="form-group">
                                <label for="oldPass">Current Password</label>
                                <input type="text" class="form-control" name="oldPass" id="oldPass" placeholder="your password">
                              </div>
                              <div class="form-group">
                                <label for="newPass">New Password</label>
                                <input type="password" class="form-control" name="newPass" id="newPass" placeholder="new password">
                              </div>
                              <div class="form-group">
                                <label for="newPassRe">Re-Enter New Password</label>
                                <input type="password" class="form-control" name="newPassRe" id="newPassRe" placeholder="confirm new password">
                              </div>
                              <button type="submit" name="changePassButt" class="btn-small colorBlueDKBtn  btn">Change</button>
                            </form>
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
<script src="assets/js/bootstrap-notify.js"></script>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="assets/js/app.js"></script>

<script src="assets/lib/require-jquery.js" data-main="assets/src/main"></script>
</html>
