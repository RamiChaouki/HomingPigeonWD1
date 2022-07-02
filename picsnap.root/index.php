<!-- insert header -->
<?php
// Stores the (string) file name of the current page in a variable to use in header.php
$currentPage = basename(__FILE__, '.php');
require 'header.php';
?>

<?php
// session_start() provided by header.php
$_SESSION["id"] = 1;
$_SESSION["email"] = "ali.nehme@gmail.com";
require_once 'includes/functions.inc.php';
$dbresult = filter_postcards();
add_postcard();
?>

<div class="container">
  <p class="text-center"><i class="text-danger">You can choose the number of postcards to order in cart before
      checkout</i></p>
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
            <img class='card-img-top' src='<?php echo "images/artist_malak/recto_" . $row['id'] . ".png" ?>'
              alt='Card image cap' title="press for more details">
          </div>
          <div class="picture2">
            <img class='card-img-top' src='<?php echo "images/artist_malak/verso_" . $row['id'] . ".png" ?>'
              alt='Card image cap' title="press for more details">
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
          <a href="#" class="btn btn-block btn-outline-success disabled"><i class="fa-solid fa-heart-circle-check"></i>
            In Favorites</a>
          <?php
              } else {
              ?>
          <form method="post" class="row">
            <button class="btn btn-block btn-primary" type="submit" name="add_to_favourites"
              value="<?php echo $row['id'] ?>"><i class="fa-solid fa-heart-circle-plus"></i> Add to
              favorites</button>
          </form>
          <?php
              }
              // cart button
              if (check_if_in_cart($row['id'])) {
              ?>
          <a href="#" class="btn btn-block btn-success disabled"><i class="fa-solid fa-check"></i> In Cart</a>
          <?php
              } else {
              ?>
          <form method="post" class="row">
            <button class="btn btn-block btn-primary" type="submit" name="add_to_cart"
              value="<?php echo $row['id'] ?>"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
          </form>
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


</body>

</html>