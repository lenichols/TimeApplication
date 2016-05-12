<?php

session_start();
//$usersDisplayName = $_REQUEST['user'];
$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$adminAuth = $_SESSION['authenticated'];
$uid = $_SESSION['uid'];

if($adminAuth == "admin" || $adminAuth == "sup"){
     include_once("../assets/server/php/config.php");
     $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
    
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="assets/js/jquery.tablesorter.js" type="text/javascript"></script>
    <script src="assets/js/jquery-ui.js" type="text/javascript"></script>
    <script src="assets/js/paging.js"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/signature_pad.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
			
			///if export is clicked
			$("#exportAllnew").click(function(){
				
				swal({   
					title: "EXPORT",   
					text: "Export the selected records?",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Export",   
					cancelButtonText: "Cancel",   
					closeOnConfirm: true,   
					closeOnCancel: true 
				}, function(isConfirm){   
					if (isConfirm) {     
						var form = $('#userschangeform'); 
						form.submit();
						 
					} else {     
						swal("Cancelled", "You have cancelled this export.", "error");   
					} 
				});

			}); //approve clicked
	    	
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
            
            $("#allCheckBoxes").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
            
            $('table.paginated').each(function() {
			    var currentPage = 0;
			    var numPerPage = 10;
			    var $table = $(this);
			    $table.bind('repaginate', function() {
			        $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
			    });
			    $table.trigger('repaginate');
			    var numRows = $table.find('tbody tr').length;
			    var numPages = Math.ceil(numRows / numPerPage);
			    var $pager = $('<div class="pager"></div>');
			    for (var page = 0; page < numPages; page++) {
			        $('<span class="page-number"></span>').text(page + 1).bind('click', {
			            newPage: page
			        }, function(event) {
			            currentPage = event.data['newPage'];
			            $table.trigger('repaginate');
			            $(this).addClass('active').siblings().removeClass('active');
			        }).appendTo($pager).addClass('clickable');
			    }
			    $pager.insertBefore($table).find('span.page-number:first').addClass('active');
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
                <a href="https://www.frontline-energy.com" class="simple-text">
                    <img src="../assets/img/festimekeeperwt-big.png" style="width: 75%;" />
                </a>
            </div>

            <ul class="nav">
                <?php if($usersRole === "admin"){ ?>
                <li>
                    <a href="./admin.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php } elseif($usersRole === "sup"){ ?>
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
                <?php } ?>
                
                <?php if($usersRole === "admin"){ ?>
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-note2"></i>
                        <p>Past Weeks</p>
                    </a>
                </li>
                <?php } elseif($usersRole === "sup"){ ?>
                <li class="active">
                    <a href="#">
                        <i class="pe-7s-note2"></i>
                        <p>Past Timesheets</p>
                    </a>
                </li>
                <?php } ?>
                
                <?php if($usersRole === "sup"){ ?>
                <li>
                    <a href="./supappr.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>Approved Time</p>
                    </a>
                </li>
                <?php } ?>
                <?php if($usersRole === "sup"){ ?>
                <li>
                    <a href="./sup.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-news-paper"></i>
                        <p>Supervisors Panel</p>
                    </a>
                </li>
                <?php } ?>
                
                <?php if($usersRole === "admin"){ ?>
                <li>
                    <a href="./users.php<?php echo '?auth='.$adminAuth.'&id='.$uid; ?>">
                        <i class="pe-7s-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                <?php } ?>
                
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
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Weekly Timesheet Entry Application</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="../killsession.php">
                                Log Out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <?php if($adminAuth == "admin") { echo "Administrator: <b>".$findDisplayAs."</b>"; } elseif ($adminAuth === "sup") { echo "Supervisor: <b>".$findDisplayAs."</b>"; } ?>
                    <br/>
                    <h3>Submitted Time (All)</h3>
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
                         <form id="userschangeform" name="userschangeform" method="post" action="../assets/server/php/updateExpLog.php">
                         <table class="main-table table table-bordered table-hover tablesorter">
                              <thead>
                               <tr class="header">
                                  <th width="10%"></th>
								  <th>ExpID</th>
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
                                  <td align="left">
                                      <input id="allCheckBoxes" type="checkbox" class="checkboxReset"> *Export All
                                  </td>
                                  <td><input name="export" type="reset" class="btn-small btn" value="Clear"></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td colspan="3" align="right"><input id="exportAllnew" name="export" type="submit" class="btn-small colorBlueDKBtn btn" value="Export to .xls"></td>

                                </tr>
                              </tfoot>
                          
                            <?php include("../assets/server/php/getRecordsAll.php"); ?>
                          
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
     <script>
			$('#search').keyup(function(){
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
</body>   
</html>
