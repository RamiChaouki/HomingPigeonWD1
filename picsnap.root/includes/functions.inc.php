<?php

/**
 * Ali Nehme
 * A function that checks if an item is in favorites
 * parameters: $postcard_id = postcard id
 */
function check_if_favourite($postcard_id)
{
    require "config/db_config.php";
    $userid = $_SESSION['id'];
    $sql = "SELECT * FROM favourites where postcard_id='$postcard_id' and user_id='$userid' ";

    $result = mysqli_query($conn, $sql);
    $numofrows = mysqli_num_rows($result);
    if ($numofrows >= 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * Ali Nehme
 * A function that returns the id of the last unpaid cart from the database
 * parameters: none
 */
function unpaid_cart_id()
{

    require "config/db_config.php";

    $userid = $_SESSION['id'];

    $cart_id_array = mysqli_query($conn, "SELECT max(id) as cart_id FROM carts WHERE user_id='$userid' AND is_paid='0'");
    $cart_id = mysqli_fetch_array($cart_id_array)["cart_id"];

    return $cart_id;
}


/**
 * Ali Nehme
 * A function that checks if an item is in unpaid cart
 * parameters: $postcard_id = postcard id
 */
function check_if_in_cart($postcard_id)
{
    require "config/db_config.php";

    $cart_id = unpaid_cart_id();

    $sql = "SELECT *
            FROM carts_postcards
            WHERE cart_id = '$cart_id'
            AND postcard_id = '$postcard_id'";

    $result = mysqli_query($conn, $sql);
    $numofrows = mysqli_num_rows($result);

    if ($numofrows >= 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * Ali Nehme
 * A function that fetches all records in a table
 * parameters: $table_name = table name
 */
function fetch_db_table($table_name)
{
    require "config/db_config.php";
    $sql = "SELECT * FROM $table_name";

    $dbresult = mysqli_query($conn, $sql);
    return $dbresult;
}

/**
 * Ali Nehme
 * A function that fetches the information of a postcard by its id
 * parameters: $postcard_id
 */
function fetch_db_table_by_id($table_name, $id)
{
    require "config/db_config.php";
    $sql = "SELECT * FROM $table_name where id='$id'";

    $dbresult = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($dbresult);
    return $row;
}

/**
 * Ali Nehme
 * A function that fetches the information of a postcard by name, description, or artist
 * parameters: $postcard_id
 */
function filter_postcards()
{
    require "config/db_config.php";
    $dbresult = fetch_db_table("postcards");

        if (!empty($_POST["name"])) {
            $name = $_POST['name'];
            $sql = "SELECT * FROM postcards WHERE name LIKE '%$name%'";

            $dbresult = mysqli_query($conn, $sql);
        }

        if (!empty($_POST['artist'])) {
            $artist = $_POST['artist'];
            $sql = "SELECT * FROM postcards WHERE artist LIKE '%$artist%'";

            $dbresult = mysqli_query($conn, $sql);
        }
    
        if (!empty($_POST["reset"])) {
            $dbresult = fetch_db_table("postcards");
        }

    return $dbresult;
}

/**
 * Ali Nehme
 * A function that adds a postcard to favourites or cart
 * parameters: none
 */
function add_postcard() {

    if(array_key_exists('add_to_favourites', $_POST)) {
       add_to_favourites($_POST['add_to_favourites']);
    }
    else if(array_key_exists('add_to_cart', $_POST)) {
        add_to_cart($_POST['add_to_cart']);
    }
}

/**
 * Ali Nehme
 * A function that adds a postcard to favourites
 * parameters: $postcard_id
 */
function add_to_favourites($postcard_id) {
    require "config/db_config.php";

        $userid = $_SESSION['id'];
        
        $addquery = "INSERT INTO favourites(user_id, postcard_id) values('$userid', '$postcard_id')";
        if(mysqli_query($conn, $addquery)){header("Refresh:0");}
    
}

/**
 * Ali Nehme
 * A function that adds a postcard to cart
 * parameters: $postcard_id
 */
function add_to_cart($postcard_id) {
    require "config/db_config.php";

        $cart_id = unpaid_cart_id();
        
        $addquery = "INSERT INTO carts_postcards(cart_id, postcard_id, quantity) values ('$cart_id', '$postcard_id', '1')";

        if(mysqli_query($conn, $addquery)){header("Refresh:0");}
}