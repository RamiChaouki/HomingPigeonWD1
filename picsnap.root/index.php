<?php
session_start();
$_SESSION["id"] = 1;
$_SESSION["email"] = "ali.nehme@gmail.com";
require_once 'includes/functions.inc.php';
$dbresult = filter_postcards();
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
            while ($row = mysqli_fetch_array($dbresult)) { ?>
                <div class="col-lg-3 mb-3 d-flex">
                    <div class="card border-dark mb-3" style="width: 18rem;">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $row['name'] ?></h4>
                            <h6 class="card-subtitle mb-2 text-muted"><i>by <?php echo $row['artist'] ?></i></h6>
                        </div>
                        <a href='<?php echo "postcard-detail.php?id=" . $row['id'] ?>'>
                            <div class="thumbnail">
                                <div class="picture1">
                                    <img class='card-img-top' src='<?php echo "images/artist_malak/recto_" . $row['id'] . ".png" ?>' alt='Card image cap' title="press for more details">
                                </div>
                                <div class="picture2">
                                    <img class='card-img-top' src='<?php echo "images/artist_malak/verso_" . $row['id'] . ".png" ?>' alt='Card image cap' title="press for more details">
                                </div>
                            </div>
                        </a>
                        <div class='card-body d-flex flex-column'>
                            <?php if (!isset($_SESSION['email'])) {  ?>
                                <p><a href="login.php" role="button" class="btn btn-primary btn-block">Login</a></p>
                            <?php
                            } else {
                            ?>
                                <div class="d-grid gap-2">
                                    <?php
                                    // favourites button
                                    if (check_if_favourite($row['id'])) {
                                    ?>
                                        <a href="#" class="btn btn-block btn-outline-success disabled"><i class="fa-solid fa-heart-circle-check"></i> In Favorites</a>
                                    <?php
                                    } else {
                                    ?>
                                        <a href='<?php echo "includes/add_to_favourites.php?origin=index&id=" . $row['id'] ?>' class="btn btn-block btn-primary" name="add" value="add"><i class="fa-solid fa-heart-circle-plus"></i> Add to favorites</a>
                                    <?php
                                    }
                                    // cart button
                                    if (check_if_in_cart($row['id'])) {
                                    ?>
                                        <a href="#" class="btn btn-block btn-success disabled"><i class="fa-solid fa-check"></i> In Cart</a>
                                    <?php
                                    } else {
                                    ?>
                                        <a href='<?php echo "includes/add_to_cart.php?origin=index&id=" . $row['id'] ?>' class="btn btn-block btn-primary" name="add" value="add"><i class="fa-solid fa-cart-plus"></i> Add to Cart</a>
                                <?php
                                    }
                                }
                                ?>
                                </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- bootstap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>