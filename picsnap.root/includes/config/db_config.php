<?php

$servername = 'localhost';
$username = 'root';
$serpass = '';
$databasename = '';


$conn = mysqli_connect($servername,$username,$serpass ,$databasename );

if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}