<?php

function isFieldEmpty($fname,$lname,$address,$email,$pwd,$pwdr){
    $result=false;
    if(empty($fname)||empty($lname)||empty($address)||empty($email)||empty($pwd)||empty($pwdr)){
        $result=true;
    }
    return $result;
}

function isFieldEmptyLogin($email,$pwd){
    $result=false;
    if(empty($email)||empty($pwd)){
        $result=true;   
    }
    return $result;
}

function isEmailValid($email){
    $result=false;
    if(!preg_match("/[\s\S]/",$email)){
        $result=true;
    }
    return $result;
}

function isPwdNotMatch($pwd,$pwdr){
    $result=false;
    if($pwd!==$pwdr){
        $result=true;
    }
    return $result;
}

function isUIDExists($conn,$email){
    $sql="Select * from users where email=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);

    $resultData=mysqli_stmt_get_result($stmt);

    if($row=mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        $result=false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}

function createNewUser($conn,$fname,$lname,$email,$address,$pwd){
    echo "i'm here";
    $sql="Insert into users (first_name,last_name,email,address,password) values (?,?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    var_dump($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    $hashedpwd=password_hash($pwd,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"ss",$fname,$lname,$email,$address,$hashedpwd);
    mysqli_stmt_execute($stmt);

    $resultData=mysqli_stmt_get_result($stmt);
    if($row=mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        $result=false;
        return $result;
    }
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
    exit();
}

function loginUser($conn,$email,$pwd){
    $uIDExists=isUIDExists($conn,$email);

    if($uIDExists===false){
        header("location: ../signup.php?error=wronguserlogin");
        exit();
    }

    $hashedPwd=$uIDExists["password"];

    $checkpwd=password_verify($pwd,$hashedPwd);
    if($checkpwd===false){
        header("location: ../login.php?error=wronguserpassword");
        exit();
    }
    else if($checkpwd===true){
        session_start();
        $_SESSION["id"]=$uIDExists["id"];
        $_SESSION["email"]=$uIDExists["email"];
        header("location: ../index.php");
        exit();
    }

}