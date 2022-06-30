<?php
session_start();
$_SESSION["id"] = 1;
$_SESSION["email"] = "ali.nehme@gmail.com";
require_once 'includes/functions.inc.php';

filter_postcards();
?>