<?php

session_start();

    $_SESSION['LOGINTO']="NOTHING";
    $_SESSION['role']="";

    session_destroy();

        sleep(3);
        header("Location: cp.php");

die();

?>
