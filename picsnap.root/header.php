<?php
    session_start();
    include_once "includes\config\db_config.php";
?>

<!DOCTYPE html>
<html lang="en">
<<head>
        <title>Sign up</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- latest compiled and minified CSS -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
        <!-- jquery library -->
        <script type="text/javascript" src="bootstrap/js/jquery-3.2.1.min.js"></script>
        <!-- Latest compiled and minified javascript -->
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
        <!-- External CSS -->
        <link rel="stylesheet" href="css/style.css" type="text/css">
    </head>
<body>
    <ul>
        <li>Homing Pigeon</li>
        <li><a href='index.php'> Home</a></li>
        
        <?php
            if(isset($_SESSION["id"])){
                echo '<li>Welcome '.$_SESSION["fname"].' </li>';
                echo '<li><a href="./includes/logout.inc.php">Logout</a></li>';
            }else{
                echo "<li><a href='login.php'>Login</a></li>";
                echo "<li><a href='signup.php'>Signup</a></li>";
            }
        ?>
        
        
    </ul>
</body>
</html>