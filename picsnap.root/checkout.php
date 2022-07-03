<!-- insert header -->
<?php
// Stores the (string) file name of the current page in a variable to use in header.php
$currentPage = basename(__FILE__, '.php');
require 'header.php';



?>

<div class="container">



<!-- Put in this a checkout button and scripts at the bottom of the page  -->
<h1>Stripe Checkout</h1>
<button class="btn btn-block btn-primary" id="btn">Checkout</button>
<script src="http://js.stripe.com/v3/"></script>
<script src="script.js"></script>
</div>







<?php
require 'footer.php';
?>