<!-- insert header -->
<?php
// Stores the (string) file name of the current page in a variable to use in header.php
$currentPage = basename(__FILE__, '.php');
require 'header.php';
?>

<h1>Stripe Checkout</h1>
<button id="btn">Checkout</button>
<script src="http://js.stripe.com/v3/"></script>
<script src="script.js"></script> 





<?php
require 'footer.php';
?>