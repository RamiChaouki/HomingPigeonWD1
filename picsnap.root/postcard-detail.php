<?php
session_start();
$_SESSION["id"] = 1;
$_SESSION["email"] = "ali.nehme@gmail.com";
require_once 'includes/functions.inc.php';
if (isset($_GET['id'])) {
    $row = fetch_db_table_by_id("postcards", $_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postcard detail</title>
    <!-- bootstap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- custom css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- font-awsome -->
    <script src="https://kit.fontawesome.com/c3d51d9f30.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="card text-center">
            <div class="card-header">
                <h4 class="card-title"><?php echo $row['name'] ?></h4>
                <h6 class="card-subtitle mb-2 text-muted"><i>by <?php echo $row['artist'] ?></i></h6>
                <div class="row">
                    <div class="col">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="true" data-bs-toggle="tab" href="#recto">Recto image</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#verso">Verso Image</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#description">Description</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <?php if (!isset($_SESSION['email'])) {  ?>
                            <p><a href="login.php" role="button" class="btn btn-primary btn-block">Login</a></p>
                        <?php
                        } else {
                        ?>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <?php
                                // favourites button
                                if (check_if_favourite($row['id'])) {
                                ?>
                                    <a href="#" class="btn btn-block btn-outline-success disabled"><i class="fa-solid fa-heart-circle-check"></i> In Favorites</a>
                                <?php
                                } else {
                                ?>
                                    <a href='<?php echo "includes/add_to_favourites.php?origin=detail&id=" . $row['id'] ?>' class="btn btn-block btn-primary" name="add" value="add"><i class="fa-solid fa-heart-circle-plus"></i> Add to favorites</a>
                                <?php
                                }
                                // cart button
                                if (check_if_in_cart($row['id'])) {
                                ?>
                                    <a href="#" class="btn btn-block btn-success disabled"><i class="fa-solid fa-check"></i> In Cart</a>
                                <?php
                                } else {
                                ?>
                                    <a href='<?php echo "includes/add_to_cart.php?origin=detail&id=" . $row['id'] ?>' class="btn btn-block btn-primary" name="add" value="add"><i class="fa-solid fa-cart-plus"></i> Add to cart</a>
                            <?php
                                }
                            }
                            ?>
                            </div>
                    </div>
                </div>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active" id="recto">
                    <img src='<?php echo "images/artist_malak/recto_" . $row['id'] . ".png" ?>' alt='<?php echo $row['name'] . " recto image" ?>' class="card-img">
                </div>
                <div class="tab-pane" id="verso">
                    <img src='<?php echo "images/artist_malak/verso_" . $row['id'] . ".png" ?>' alt='<?php echo $row['name'] . " verso image" ?>' class="card-img">
                </div>
                <div class="tab-pane" id="description">
                    <p class=" card-text"><?php echo $row['description'] ?></p>
                </div>
            </div>
        </div>
    </div>


    <?php
    function testfun()
    {
        echo "Your test function on button click is working";
    }
    if (array_key_exists('test', $_POST)) {
        testfun();
    } ?>
    <!-- bootstap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>