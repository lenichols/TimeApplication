<?php
	
ini_set("display_errors",1); 
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');  


session_start();
//$usersDisplayName = $_REQUEST['user'];
$findDisplayAs = $_SESSION['DisplayAs'];
$usersRole = $_SESSION['role'];
$empID = $_SESSION['empID'];
$uid = $_SESSION['uid'];
$findDeniedRec = (!empty($_GET['recid'])) ? $_GET['recid'] : null;
$getPgName = "Weekly Timesheet Entry Application";

/*
echo $findDisplayAs."<br/>";
echo $usersRole."<br/>";
echo $empID."<br/>";
echo $uid."<br/>";
echo $findDeniedRec."<br/>";
echo $getPgName."<br/>";
*/

if(empty($empID)){
    header('Location: ../killsession.php');
} else {
		
		if(isset($_GET['recid'])){
		
			include_once("../assets/server/php/config.php");
			$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
	        $stmtDenied = $conn->query("SELECT * FROM records WHERE id = '".$findDeniedRec."'");
	        $d = $stmtDenied->fetch();
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
    <script src="dist/sweetalert.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">
 

    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/signature_pad.js"></script>
	<script type="text/javascript">
    	$(document).ready(function(){
            
                    var timedfields;

                    function listen() {
                        timedfields = setInterval(listenToFields, 1000);
                    } 

                    function listenToFields(){
                        
                        console.log("working");
            
                        var maxreg = 8;
                        var maxOT = 4;
                        var maxDT = 12;
                        $('[id^=timeIn]').keyup(function(){

                            var inputValue = $(this).val();
                            if(inputValue > maxreg){
                                
                                document.activeElement.blur();
                                
                                swal({   
                                    title: "Alert",   
                                    text: "no more than 8 hours allowed Regular Time",   
                                    timer: 3000,   
                                    showConfirmButton: false 
                                });

                                $(this).val('');
                            }
                        });

                        $('[id^=timeOT]').keyup(function(){

                            var inputValue = $(this).val();
                            if(inputValue > maxOT){
                                
                                document.activeElement.blur();
                                
                                swal({   
                                    title: "Alert",   
                                    text: "no more than 4 hours allowed OT",   
                                    timer: 3000,   
                                    showConfirmButton: false 
                                });

                                $(this).val('');
                            }
                        });

                        $('[id^=timeDT]').keyup(function(){

                            var inputValue = $(this).val();
                            if(inputValue > maxDT){
                                
                                document.activeElement.blur();
                                
                                swal({   
                                    title: "Alert",   
                                    text: "no more than 12 hours DT allowed",   
                                    timer: 3000,   
                                    showConfirmButton: false 
                                });

                                $(this).val('');
                            }
                        });
                        
                        
                    }

					function timeAdded(){
						swal("Time entry added!");
					}

					function timeAddedReSub(){
						swal("Time entry re-submitted!");
					}

					function SigRequired(){
						swal('A signature is required!');
					}

					var nothingWasInserted = function emptyData(){
						swal('Your time entry was blank!');
					}

					function refreshPgNow(){

					}

					$("input[id^=date]").on("change", function(){
						console.log($("input[id^=date]").val());
					});

					if (localStorage["signature"]) {
							$('#sig').val(localStorage["signature"]);
					 }

					$('input[type=text], input[type=date], select, textarea, .ta').each(function(){    
						var name = $(this).attr('name');
						var value = localStorage.getItem(name);
						$(this).val(value);

					});  

					$('input[type=radio]').each(function(){ 
						var name = $(this).attr('name'); 
						var radios = document.getElementsByName(name); 
						var val = localStorage.getItem(name);
						for(var i=0;i<radios.length;i++){
						  if(radios[i].value == val){
							  radios[i].checked = true;
						  }
						}
					});

                   $('input[type=text], input[type=date], input[type=time], select, textarea, .ta, .ddrop').change(function(){    
                        var name = $(this).attr('name');
                        var value = $(this).val();
                       localStorage.setItem(name, value);

                    }); 

                    $("input:radio[type=radio]:checked").each(function() {
                            var value = $(this).val();
                            var name = $(this).attr('name');
                           localStorage.setItem(name, value);
                           //alert(value);
                    });

                    $("input[type=checkbox]").click(function() { 
                            if ($(this).is(':checked')) {
                                $this = $(this);
                                localStorage[$this.attr("name")] = $this.checked = true;
                                //$(this).prop('checked',false);
                                //alert("is checked");
                            }
                            if($(this).is(':not(:checked)')) {
                                $this = $(this);
                                localStorage.removeItem([$this.attr("name")]);
                            }
                        });

                    $("select option:checked").change(function() {
                            var value = $(this).val();
                            var name = $(this).attr('name');
                           localStorage.setItem(name, value);
                           //alert(value);
                    });

         <?php if($findDeniedRec) { ?>

         function initDenied(){
	         
			    var rowNum = 1;
		        function addRow(frm) {
		        //rowNum ++;
		
		         var newtr = '<tr class="day" data-bind="css:{error: rowTotal() < 0}"><td align="center" style="width: 5rem;"><input id="date" name="date" type="date" class="form-control datepicker short70" value="<?php if($findDeniedRec){echo $d['date'];} ?>"></td><td align="center"><select id="proj" name="proj" class="ddown form-control"><option value="<?php if($findDeniedRec){echo $d['project'];} ?>"><?php if($findDeniedRec){echo $d['project'];} ?></option></select></td><td align="center"><input type="text" id="wr" name="wr" class="form-control short" style="width: 15rem;"  value="<?php if($findDeniedRec){echo $d['wrnum'];} ?>"></td><td align="center""><input size="10" type="number" id="timeIn" name="regTime" class="required form-control short" value="<?php if($findDeniedRec){echo $d['stHours'];} ?>" data-bind="value:regTime, click:cellClick, event:{blur:cellBlur}"></td><td align="center" style="width: 5rem;"><input type="number" size="10" id="timeOT" name="timeOT"  class="number form-control short"   value="<?php if($findDeniedRec){echo $d['otHours'];} ?>" data-bind="value:OTLen, click:cellClick, event:{blur:cellBlur}"></td><td align="center" style="width: 5rem;"><input type="number"size="10" id="timeDT" name="timeDT" class="number form-control short"  value="<?php if($findDeniedRec){echo $d['dtHours'];} ?>" data-bind="value:DTLen, click:cellClick, event:{blur:cellBlur}"></td><td style="width: 15rem;"><select id="timeTask" name="timeTask" class="typ form-control"><?php if($findDeniedRec === "billable"){echo '<option value="billable">Billable</option><option value="unpaid">Unpaid</option><option value="overhead">Overhead</option><option value="training">Training</option>'; } else if($findDeniedRec === "unpaid") { echo'<option value="unpaid">Unpaid</option><option value="billable">Billable</option><option value="overhead">Overhead</option><option value="training">Training</option>'; } else if ($findDeniedRec === "overhead") { echo '<option value="overhead">Overhead</option><option value="unpaid">Unpaid</option><option value="billable">Billable</option><option value="training">Training</option>'; } else if ($findDeniedRec === "training") { echo '<option value="training">Training</option><option value="overhead">Overhead</option><option value="unpaid">Unpaid</option><option value="billable">Billable</option>'; } else { echo '<option></option><option value="training">Training</option><option value="overhead">Overhead</option><option value="unpaid">Unpaid</option><option value="billable">Billable</option>'; } ?></select></td><td align="center"><div class="day-total"   data-bind="value:rowTotal" ><input id="totalHrs" name="totalHrs" type="input" value="<?php if($findDeniedRec){echo $d['hrsTotal'];} ?>" data-bind="value:rowTotal" style="border: none;" disabled></div></td></tr><tr style="border-bottom: 5px solid #40668A;"><td colspan="8"><textarea id="notes" name="notes" placeholder="additional notes" class="form-control notesTxtField"><?php if($findDeniedRec){echo $d['notes'];} ?></textarea><input name="foundID" type="hidden" value="<?php if($findDeniedRec){echo $d['id'];} ?>" ></td></tr>'; 
         
		         //$("#data tbody").after(newtr);
		         	jQuery('#data tbody').append(newtr);
		         	
		         	var Json_cliReturn = [];
		         	
			        jQuery(function(){
			            jQuery.getJSON('../assets/server/php/getClients.php',{},function(data){
			                Json_cliReturn = data.clientData;
			                    //jQuery('[id^=proj]').eq(0).html('');
			                    //jQuery('[id^=proj]').eq(0).html('<option></option>');
			                        jQuery(Json_cliReturn).each(function(indx,Val){
			                            jQuery('[id^=proj]').eq(0).append('<option value="'+Val.name+'">'+Val.name+'</option>');
			                        });
			            });
			        });
		        
		         } addRow();
         
         } initDenied();
		
		<?php } else { ?>
            
        var rowNum = 0;
        function addRow(frm) {
        rowNum ++;

                        var row = '<tr class="day" data-bind="css:{error: rowTotal() < 0}"><td align="center" style="width: 5rem;"><input id="date[]" name="date[]" type="date" class="form-control datepicker short70" ></td><td align="center"><select id="proj[]" name="proj[]" class="ddown form-control"><option></option></select></td><td align="center"><input type="text" id="wr[]" name="wr[]" class="form-control short" style="width: 15rem;"></td><td align="center""><input maxlength="3" size="3" type="number" id="timeIn[]" name="regTime[]" class="required form-control short" data-bind="value:regTime, click:cellClick, event:{blur:cellBlur}"></td><td align="center" style="width: 5rem;"><input type="number" size="2" id="timeOT[]" name="timeOT[]"  class="number form-control short" data-bind="value:OTLen, click:cellClick, event:{blur:cellBlur}"></td><td align="center" style="width: 5rem;"><input type="number" size="2" id="timeDT[]" name="timeDT[]" class="number form-control short" data-bind="value:DTLen, click:cellClick, event:{blur:cellBlur}"></td><td style="width: 15rem;"><select id="timeTask[]" name="timeTask[]" class="typ form-control"><option></option><option value="Billable">Billable</option><option value="Training">Training</option><option value="Overhead">Overhead</option><option value="Unpaid">Unpaid</option></select></td><td align="center"><div class="day-total" data-bind="text:rowTotal"></div><input id="totalHrs[]" name="totalHrs[]" type="hidden" data-bind="value:rowTotal"></td></tr><tr style="border-bottom: 5px solid #40668A;"><td colspan="8"><textarea id="notes[]" name="notes[]" placeholder="additional notes" class="form-control notesTxtField"></textarea></td></tr>';

        jQuery('#data tbody').append(row);
            
        //fetch clients from clients table and display in Proj dropdown
        var Json_cliReturn = [];
        jQuery(function(){
            jQuery.getJSON('../assets/server/php/getClients.php',{},function(data){
                Json_cliReturn = data.clientData;
                    jQuery('[id^=proj]').eq(0).html('');
                    jQuery('[id^=proj]').eq(0).html('<option></option>');
                        jQuery(Json_cliReturn).each(function(indx,Val){
                            jQuery('[id^=proj]').eq(0).append('<option value="'+Val.name+'">'+Val.name+'</option>');
                        });
            });
        });

        } addRow();
        
        <?php } ?>
            
         $(document).on('change', '[id^=date]', function(e) {
             if ($(this).val().length >  0) {
                $(this).closest("tr").next("tr").find('[id^=notes]').addClass( "requiredRed" );
                $(this).closest("tr").next("tr").find('[id^=notes]').prop("required", true);
                
                /*$(this).closest('tr').find('[id^=notes]').each(function(){
	                $(this).prop("required", true);
	                $(this).addClass( "requiredRed" );
	            });*/
                
                $(this).closest('tr').find('[id^=timeTask]').each(function(){
	                $(this).prop("required", true);
	                $(this).addClass( "requiredRed" );
	            });
	            
	            $(this).closest('tr').find('[id^=proj]').each(function(){
	                $(this).prop("required", true);
	                $(this).addClass( "requiredRed" );
	            });
	            
	            $(this).closest('tr').find('[id^=wr]').each(function(){
	                $(this).prop("required", true);
	                $(this).addClass( "requiredRed" );
	            });
             }
         });
            
         $(document).on('change', '[id^=proj]', function(e) {
             if ($(this).val().length >  0) {
                $(this).closest("tr").next("tr").find('[id^=notes]').addClass( "requiredRed" );
             }

             if($(this).val() == "SWG"){
                 console.log("works");
                  $('[id^=timeDT]').attr('readonly', 'readonly');
                  //$(this).closest('tr').find('[id^=timeTask]').empty();
                  $(this).closest('tr').find('[id^=timeTask]').each(function(){
                     //$(this)   //select box of same row
                      $(this).find("option[value='Billable']").remove();
                      $(this).find("option[value='Training']").remove();
                      $(this).find("option[value='Overhead']").remove();
                      $(this).find("option[value='Unpaid']").remove();
                      $(this).find(":selected").text("Billable").val("Billable");
                  });
             } else {
                 
                 $("[id^=timeDT]").removeAttr('readonly');
                 $(this).closest('tr').find('[id^=timeTask]').each(function(){
                     $(this).find("option[value='Billable']").remove();
                  });
             }
        });

        $('body').append('<div id="toTop" class="btn btn-info"><i class="fa fa-plus"></i>Add Row</div>');

        ////addnew row button
        <?php if($findDeniedRec) { ?>
        
        		$("div#toTop").hide();
        
        <?php } else { ?>
        
        		$.notify({
	            	icon: 'pe-7s-gift',
	            	message: "Welcome to the new <b>Field Inspection Time Entry system</b> - Be sure to to click 'Save' on any data inserted. If you are ready to submit your timecard for the week, press 'submit'."
	
	            },{
	                type: 'info',
	                timer: 2000
	            });
        
		        var i = 1;
		        $("div#toTop").click(function() {
		            
		          $("table#data tr:eq(-2)").clone().find("input, select, textarea").each(function() {
		              console.log("clicked toTop");
		            $(this).attr({
		              'id': function(_, id) { return id + i },
		              'name': function(_, name) { return name + i },
		              'value': ''               
		            });
		          }).end().appendTo("table tbody");
		          i++;
		        });
		            
		        var i = 1;
		        $("div#toTop").click(function() {
		            
		          $("table#data tr:eq(-2)").clone().find("input, select, textarea").each(function() {
		              console.log("clicked toTop");
		            $(this).attr({
		              'id': function(_, id) { return id + i },
		              'name': function(_, name) { return name + i },
		              'value': ''               
		            });
		          }).end().appendTo("table tbody");
		          i++;
		        });
            
        <?php } ?>
            
    	});
	</script>
    <script type="text/javascript">

        function validateForm(){
            var form_valid = localStorage["signature"]; 
            if(form_valid == null){
                console.log("false");
                swal('A signature is required!');
                return false;
            }

            var unfilled = $('[required]').filter(function() {
			    return $(this).val().length === 0;
			});
			
			
			if (unfilled.length > 0) {
			    // still unfilled inputs
			    unfilled.css('border', '1px solid red');
			    
			    swal({   title: "Alert!",   text: "Notes, Project and Task are required before you submit time!",   timer: 2000,   showConfirmButton: false });
			    
			} else {
				
				 if($('[id^=date]').length == 0){
					 alert("nothing inserted");
				 } else {
					 $('#sendWeeklyTs').submit();
				 }
            }

        }
        
        
        
        
    </script>
    
    <style>
	    @media (max-width: 1025px) {
			.navbar-header {
				float: none;
			}
			.navbar-toggle {
				display: block;
			}
			.navbar-collapse {
				border-top: 1px solid transparent;
				box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
			}
			.navbar-collapse.collapse {
				display: none!important;
			}
			.navbar-collapse.collapse.in {
				display: block!important;
			}
			.navbar-nav {
				float: none!important;
				margin: 7.5px -15px;
			}
			.navbar-nav>li {
				float: none;
			}
			.navbar-nav>li>a {
				padding-top: 10px;
				padding-bottom: 10px;
			}
		}
		
	    button.navbar-toggle {
		    cursor:pointer;
		}
        .requiredRed {
            outline: none !important;
            border:1px solid red;
            box-shadow: 0 0 10px #719ECE;
        }
        
        #toTop{ position: fixed; top: 183px; right: 35px; z-index: 999; }
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
                <li class="active">
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
                <li>
                    <a href="./userinfo.php<?php echo '?id='.$uid; ?>">
                        <i class="pe-7s-note2"></i>
                        <p>My Info</p>
                    </a>
                </li>
                <!--
                <li>
                    <a href="#">
                        <i class="pe-7s-user"></i>
                        <p>User Info</p>
                    </a>
                </li>
                -->
                <!-- if users role is admin, show admin button -->
                <?php if($usersRole === "admin"){ ?>
                <li>
                    <a href="admin.php">
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
                    <form id="sendWeeklyTs" method="post" action="../assets/server/php/insertWeekly.php?sup=<?php echo $_SESSION['supervisor']; ?>">
                    <div class="pull-left">
                        <h3>Field Services Team        
                        </h3>
                    <?php echo "Time for: <b>".$findDisplayAs."</b>"; ?>
                        <br/>
                        <small style="color: #0e4880;"><b>Need more rows?</b> To add more rows, enter time in the last row and a new row will be automatically inserted</small>
                    </div>
                    <div id="topSave" class="pull-right"><!--<input type="button" class="btn-lg colorBlueBtn btn" value="SAVE">&nbsp;&nbsp;--><input type="button" class="btn-lg colorBlueDKBtn btn" <?php if($findDeniedRec) { echo 'value="RE-SUBMIT"'; } else { echo 'value="SUBMIT"';} ?> onclick='validateForm()' name="submitNow">
                    </div>
                    <div class="table-wrapper col-xs-12">
                      <?php 
                        if(isset($_GET['sub'])){ 
                            echo "<script type='text/javascript'>timeAdded();</script>";
                            echo '<span style="color:green;">Time submitted!</span>'; 
                        }
                        if(isset($_GET['ressub'])){ 
                            echo "<script type='text/javascript'>timeAddedReSub();</script>";
                            echo '<span style="color:red;">Time re-submitted!</span>'; 
                        }
                        if(isset($_GET['sig']) === "false"){ 
                            echo "<script type='text/javascript'>SigRequired();</script>";
                            echo '<span style="color:red;">A signature is required!</span>'; 
                        }
						
                      ?>
                      <table id="data" class="main-table table table-bordered table-hover">
                          <thead>
                            <tr class="header">
                              <th>Day</th>
                              <th>Project</th>
                              <th>WR#</th>
                              <th>Reg(Hrs)</th>
                              <th>OT(Hrs)</th>
                              <th>DT(Hrs)</th>
                              <th>Task</th>
                              <th>Hours</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr class="total">
                              <td class="white-button" colspan="1">
                                <input type="button" class="btn-small btn" 
                                       value="clear" data-bind='click:clearClick'>
                              </td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="total-label">Total</td>
                              <td><div class="week-total" data-bind='text:grandTotal'>0</div></td>
                            </tr>
                          </tfoot>
                          <tbody data-bind='foreach: rows'>
                            
                          </tbody>
                      </table>
                      <div class="col-md-8" style="padding: 0;">
                      <h3 style="display: inline;">Signature Required</h3> <small style="display: inline;">(*press save before submit)</small>
                      <div id="signature-pad" class="m-signature-pad">
	                      <br/>
                        <div class="m-signature-pad--body">
                          <canvas></canvas>
                        </div>
                        <div class="m-signature-pad--footer">
                          <div class="description">Sign above</div>
                            <input type="button" class="btn-small colorBlueBtn clear button btn" value="Clear" data-action="clear">
                          <input type="button" class="btn-small colorBlueBtn save button btn" value="Save" data-action="save">
                        </div>
                      </div>
                      </div>
                        
                        
                        
                    <div class="col-md-4 buttonBox">  
                    <div class="pull-right"><!--<input id="saveRec" type="button" class="btn-lg colorBlueBtn btn" value="SAVE">&nbsp;&nbsp;--><input type="button" class="btn-lg colorBlueDKBtn btn" <?php if($findDeniedRec) { echo 'value="RE-SUBMIT"'; } else { echo 'value="SUBMIT"';} ?>  onclick='validateForm()' name="submitNow">
                    </div>
                    </div>
                    <script>
                        
                    </script>
                        
                </div>
                <input id="inspName" name="inspName" type="hidden" value="<?php echo $findDisplayAs; ?>" />
                <input id="empID" name="empID" type="hidden" value="<?php echo $empID; ?>" />
                <input id="sig" name="sig" type="hidden" />
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

	<?php if($findDeniedRec) { ?>
	
	<script src="assets/lib/moment.min.js" data-main="assets/src/mainRes"></script>
	
	<?php } else { ?>
	
	<script src="assets/lib/moment.min.js" data-main="assets/src/main"></script>
	
	<?php } ?>
    
    
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/app.js"></script>
    <script src="assets/js/moment-with-locales.min.js" type="text/javascript"></script>
	
    <script src="assets/js/sp.js"></script>
    <?php if($findDeniedRec) { ?>
	
	<script src="assets/lib/require-jquery.js" data-main="assets/src/mainRes"></script>
	
	<?php } else { ?>
	
	<script src="assets/lib/require-jquery.js" data-main="assets/src/main"></script>
	
	<?php } ?>
</html>