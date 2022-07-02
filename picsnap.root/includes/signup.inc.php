<?php

if(isset($_POST['submit'])){
    $fname=$_POST["first_name"];
    $lname=$_POST["last_name"];
    $email=$_POST["email"];
    $address=$_POST["address"];
    $pwd=$_POST["password"];
    $pwdr=$_POST["confirm_password"];

    include_once 'C:\XAMPP\htdocs\HomingPigeonWD1\picsnap.root\includes\config\db_config.php';
    require_once 'C:\XAMPP\htdocs\HomingPigeonWD1\picsnap.root\includes\functions.inc.php';
    
    if(isFieldEmpty($fname,$lname,$email,$address,$pwd,$pwdr)!==false){
        header("location: ../signup.php?error=emptyfield");
        exit();
    }

    if(isEmailValid($email)!==false){
        header("location: ../signup.php?error=invalidemail");
        exit();
    }

    if(isPwdNotMatch($pwd,$pwdr)!==false){
        header("location: ../signup.php?error=pwddontmatch");
        exit();
    }

    if(isUIDExists($conn,$email)!==false){
        header("location: ../signup.php?error=useralreadyexists");
        exit();
    }

    createNewUser($conn,$fname,$lname,$email,$address,$pwd);
}
else{
    header("location: ../signup.php");
    exit();
}