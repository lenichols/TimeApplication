<?php



?>

<nav class="navbar navbar-default navbar-fixed">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $getPgName; ?></a>
        </div>
        <div id="navbar" class="navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
				
            </ul>

            <ul class="nav navbar-nav navbar-right">
	             <!--<li>
                    <a href="./dashboard.php<?php echo '?id='.$uid; ?>">
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="./userspast.php<?php echo '?id='.$uid; ?>">
                        <p>Past Weeks</p>
                    </a>
                </li>
                <li>
                    <a href="./userinfo.php<?php echo '?id='.$uid; ?>">
                        <p>My Info</p>
                    </a>
                </li>-->

                <li>
                    <a href="../killsession.php">
                        Log Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>



