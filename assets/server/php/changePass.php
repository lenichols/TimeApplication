<?php

if(isset($_POST["changePassButt"])){
    try {
            include_once("config.php");

            $findDisplayAs = $_SESSION['DisplayAs'];
            $uid = $_SESSION['uid'];
            $password= hash('sha256', $_POST['newPass']);
            $password2= hash('sha256', $_POST['newPassRe']);
            $old_password= hash('sha256', $_POST['oldPass']);
        
            $status = "OK";
            $msg = "";

            $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbusername,$dbpassword);
            $count = $conn->prepare("SELECT pass FROM users where id= :uuid");
            $count->bindParam(':uuid', $uid, PDO::PARAM_INT);
            $count->execute();
            $row = $count->fetch(PDO::FETCH_OBJ);
        
            if($row->pass != $old_password){
                $msg=$msg."Your old password is does not match our database. Enter a correct password.<BR>";
                $status= "NOTOK";
            }
        
            if ( $password != $password2 ){
                $msg=$msg."Both passwords must match.<BR>";
                $status= "NOTOK";
            }
        
            if($status != "OK"){ 
                    echo "<span style='width: 100%;display: inline-block;color: red;'><i>$msg</i>&nbsp;<input type='button' value='Retry' class='btn-small btn' onClick='history.go(-1)'></span>";

                    }else{ // if all validations are passed.

                    $password=$password; // Encrypt the password before storing
                    $sql=$conn->prepare("update users set pass=:password where id='$uid'");
                    $sql->bindParam(':password',$password,PDO::PARAM_STR, 32);
                        if($sql->execute()){
                            echo "<script>passwordChangeSuccess();</script>";
                            echo "<span style='color: darkblue;'><i>Password changed successfully!</i>";
                        }else{

                            echo "<span style='color: red;'><i>Something went wrong! Password not updated</i>";
                        }
            }

        } catch(PDOException $e) {
          echo $e->getMessage();
    }
}

?>