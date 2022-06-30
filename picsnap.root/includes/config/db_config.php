<?php

$servername = 'localhost';
$username = 'root';
$serpass = '';
$databasename = 'homing_pigeon';


$conn = mysqli_connect($servername,$username,$serpass ,$databasename );

if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}