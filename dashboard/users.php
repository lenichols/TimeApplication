<?php

session_start();
//$usersDisplayName = $_REQUEST['user'];
$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$adminAuth = $_SESSION['authenticated'];
$uid = $_SESSION['uid'];

if($adminAuth != "admin"){
    header('Location: ../killsession.php');
} else {
    include_once("../assets/server/php/config.php");
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
    $getPgName = "Weekly Timesheet Entry Application";
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
                    <a href="./admin.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
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
                <li class="active">
                    <a href="./users.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                <!-- if users role is admin, show admin button -->
                 <?php if($adminAuth === "admin"){ ?>
                <li>
                    <a href="./admin.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
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
                    <h3>All Users</h3>
                    <div class="panel panel-default">
                      <div class="panel-body">
                          <form class="form-horizontal" role="form">
                              <div class="form-group pull-right">
                                <label for="input1" class="col-lg-6 control-label" style="font-size: 16px;">Search Users:</label>
                                <div class="col-lg-6">
                                  <input type="text" id="search" class="form-control" style="" placeholder="search..."></input>
                                </div>
                              </div>
                          </form>  
                          
                         <form name="userschangeform" method="post">
                         <table class="main-table table table-bordered table-hover tablesorter">
                              <thead>
                                <tr class="header">
                                  <th width="10%"></th>
                                  <th width="20%">Employee Name</th>
                                  <th>Role</th>
                                  <th>ID</th>
								  <th>Username</th>
                                  <th>Region</th>
                                  <th>Supervisor</th>
                                  <th width="14%">Status</th>
                                </tr>
                              </thead>
                              <tfoot>
                                <tr class="total">
                                  <td colspan="7" align="left"><input name="updateUsers" type="submit" class="btn-small colorBlueBtn pull-left btn" value="Update">&nbsp;<input name="delUsers" type="submit" class="btn-small colorBlueDKBtn pull-right btn" value="Delete"></td>
                                </tr>
                              </tfoot>
                              <tbody>
                                <?php include("../assets/server/php/getUserList.php"); ?>
                              </tbody>
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
    <script>
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
    </script>
    <script src="assets/lib/moment.min.js" data-main="assets/src/main"></script>
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/app.js"></script>
	
    <script src="assets/lib/require-jquery.js" data-main="assets/src/main"></script>    
</html>
