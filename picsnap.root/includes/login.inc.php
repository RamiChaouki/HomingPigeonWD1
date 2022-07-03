<?php

if(isset($_POST['submit'])){
    $uname=$_POST["email"];
    $pwd=$_POST["pwd"];

    require_once './config/db_config.php';
    require_once 'functions.inc.php';

    if(isFieldEmptyLogin($email,$pwd)!==false){
        header("location:../login.php?error=emptyfield");
        exit();
    }

    loginUser($conn,$email,$pwd);
}
else{
    header("location: ../login.php");
}
