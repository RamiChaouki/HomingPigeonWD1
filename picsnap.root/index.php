<?php
session_start();
$_SESSION["id"] = 1;
$_SESSION["email"] = "ali.nehme@gmail.com";
require_once 'includes/functions.inc.php';
$dbresult = filter_postcards();
add_postcard();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postcards</title>
    <!-- bootstap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- custom css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- font-awsome -->
    <script src="https://kit.fontawesome.com/c3d51d9f30.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <p class="text-center"><i class="text-danger">You can choose the number of postcards to order in cart before checkout</i></p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row">
            <div class="col">
                <input type="text" name="name" class="form-control">
            </div>
            <div class="col">
                <input type="submit" value="Filter by name" class="btn btn-primary">
            </div>
            <div class="col">
                <input type="text" name="artist" class="form-control">
            </div>
            <div class="col">
                <input type="submit" value="Filter by artist" class="btn btn-primary">
            </div>
            <input type="submit" name="reset" value="Reset" class="btn btn-primary col">
        </form>
        <br>
        <div class="row">
            <?php
            create_postcard_cards($dbresult)
             ?>
        </div>
    </div>

    <!-- bootstap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>