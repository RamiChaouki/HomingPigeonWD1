<?php
include_once "header.php";
?>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <h1><b>SIGN UP</b></h1>
            <form method="post" action="includes/signup.inc.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="first_name" placeholder="First Name" required="true">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" required="true">
                </div>                
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required="true" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="address" placeholder="Address" required="true">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password(min. 6 characters)" required="true" pattern=".{6,}">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required="true" pattern=".{6,}">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="submit" value="Sign Up">
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'emptyfield') {
        echo '<h2> Please fill in all the fields</h2>';
    }
    if ($_GET['error'] == 'invalidemail') {
        echo '<h2> Please enter a valid email</h2>';
    }

    if ($_GET['error'] == 'pwddontmatch') {
        echo '<h2> Please write the same password in both password fields</h2>';
    }

    if ($_GET['error'] == 'emailalreadyexists') {
        echo '<h2> Email already exists</h2>';
    }

    if ($_GET['error'] == 'none') {
        echo '<h2> You have successfully signed up!</h2>';
    }
}



<!-- insert header -->

// Stores the (string) file name of the current page in a variable to use in header.php
$currentPage = basename(__FILE__, '.php');

require 'header.php';
?>


