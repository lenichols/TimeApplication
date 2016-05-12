<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favico.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>FES TimeKeeper App</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="viewport" content="width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" type="text/css" href="dashboard/dist/sweetalert.css">
<style>
body{
	margin: 0 !important;
	height: 100vh;
	width: 100vw;
	display: -webkit-flex;
	display: flex;
	-webkit-justify-content: center;
	justify-content: center;
	-webkit-align-items: center;
	align-items: center;
    background-color: #ECF0F1;
	background-size: cover;
	background-position: center;
}	
#signin{
	display: -webkit-flex;
	display: flex;
	-webkit-align-self: center;
	align-self: center;
	-webkit-flex-direction: column;
	flex-direction: column;
	padding: 1em;
	margin: 0 !important;
	background-color: #ECF0F1;
	-webkit-border-radius: .3em;
	border-radius: .3em;
	-webkit-box-shadow: 0px 2px 7px rgba(0, 0, 0, 0.40);
	box-shadow: 0px 2px 7px rgba(0, 0, 0, 0.40);
}
#signin input{
    width:22em;	
	height: 3em;
    font-size: 1em;
    font-weight: lighter !important; /* FIREFOX */
	display: block;
	margin-bottom: .5em;
	outline: none;
	text-align: center;
	background-color: #fff;	
	border: 1px solid #d5dadc;
	-webkit-border-radius: 2px;
	border-radius: 2px;
	padding: 0 !important; /* FIREFOX */
}
#signin input:-webkit-autofill{
    -webkit-box-shadow: 0 0 0px 1000px white inset;
}
#signin input[type="text"]{
	-webkit-text-fill-color: #7C7C7C !important;
}
#signin input[type="password"]{
	-webkit-text-fill-color: #7C7C7C !important;
}
button[type=submit]{
	height: 3em;
	width: 100%;
	border: 1px solid  rgb(14,72,128);
    color: #fff;
    background-color: rgb(14,72,128);
    cursor: pointer;
	font-size: 1em;
    font-weight: bold;
    outline: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-family: HelveticaNeue-Light,"Helevetica Neue",Helvetica,Arial;
}
button[type=submit]:hover{
	background-color: rgb(38, 95, 148);
}
button[type=submit]:active{
	-webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) inset;
	-moz-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) inset;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) inset;
}	
img{
	width: 20em;
	height: 5em;
	padding: 0 1em 1em 1em;
}
    
.errorBox {
    width: 300px; 
    margin: auto auto;
    color: red; 
    text-align: center;
    font-family: HelveticaNeue-Light,"Helevetica Neue",Helvetica,Arial;
}
    
footer {
    font-family: HelveticaNeue-Light,"Helevetica Neue",Helvetica,Arial;
    text-align: center;
    font-size: .80em;
    margin-top: 5%;
}
</style>
</head>
<body>
    <div>
    <form id='signin' action="./assets/server/php/checkLogin.php" method="post">
		<img src="./assets/img/festimekeeper-big.png" id="logo">
		<input type='text' name='name' id='name' placeholder="username">
		<input type='password' name='pass' id='pass' placeholder="password">
		<button type="submit" id='button btn-submit'>Sign In</button>
        <div class="errorBox">
            <?php 
                if($_GET['res'] === "wp"){ 
                    echo '<br/>Password Incorrect'; 
                } elseif ($_GET['res'] === "na"){
                    echo '<br/>You are not authorized to access this area!'; 
                } 
            ?>
        </div>
	</form>
    <footer>Unauthorized use of this system is strictly prohibited.<br/>Questions about this system contact FES <a href="maiilto: helpdesk@g2-is.com">Help Center</a>.</footer>
    </div>
    <script src="assets/js/jquery-1.12.1.min.js"></script>
    <script>
        $(document).ready(function(){
            
            $('input[type=text]').focus(function() {
                if (this.value == this.value) {
                    $(this).val("");
                }
            }).blur(function() {
                if (this.value == "") {
                    $(this).val(this.value);
                }
            });
        });
    </script>
</body>
</html>