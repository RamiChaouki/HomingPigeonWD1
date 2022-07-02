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
function add_postcard()
{

  if (array_key_exists('add_to_favourites', $_POST)) {
    add_to_favourites($_POST['add_to_favourites']);
  } else if (array_key_exists('add_to_cart', $_POST)) {
    add_to_cart($_POST['add_to_cart']);
  }
}

/**
 * Ali Nehme
 * A function that adds a postcard to favourites
 * parameters: $postcard_id
 */
function add_to_favourites($postcard_id)
{
  require "config/db_config.php";

  $userid = $_SESSION['id'];

  $addquery = "INSERT INTO favourites(user_id, postcard_id) values('$userid', '$postcard_id')";
  if (mysqli_query($conn, $addquery)) {
    header("Refresh:0");
  }
}

/**
 * Ali Nehme
 * A function that adds a postcard to cart
 * parameters: $postcard_id
 */
function add_to_cart($postcard_id)
{
  require "config/db_config.php";

  $cart_id = unpaid_cart_id();

  $addquery = "INSERT INTO carts_postcards(cart_id, postcard_id, quantity) values ('$cart_id', '$postcard_id', '1')";

  if (mysqli_query($conn, $addquery)) {
    header("Refresh:0");
  }
}

/**
 * Ali Nehme
 * A function that creates bootstrap cards of postcards fetched from database
 * parameters: $dbresults
 */
function create_postcard_cards($dbresult)
{
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
        <a href="#" class="btn btn-block btn-outline-success disabled"><i class="fa-solid fa-heart-circle-check"></i> In
          Favorites</a>
        <?php
              } else {
              ?>
        <form method="post" class="row">
          <button class="btn btn-block btn-primary" type="submit" name="add_to_favourites"
            value="<?php echo $row['id'] ?>"><i class="fa-solid fa-heart-circle-plus"></i> Add to favorites</button>
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
          <button class="btn btn-block btn-primary" type="submit" name="add_to_cart" value="<?php echo $row['id'] ?>"><i
              class="fa-solid fa-cart-plus"></i> Add to Cart</button>
        </form>
        <?php
              }
            }
            ?>
      </div>
    </div>
  </div>
</div>
<?php
  }
}

/**
 * Rami Chaouki
 * A function that displays rows of all the users at homing_pigeon that is editable and deletable 
 */
function displayUserRows($conn){
    $sql = 'select * from users';
            $result = mysqli_query($conn, $sql);
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($rows as $row) {
                extract($row);
                if(isset($_GET['id']) &&$id===$_GET['id']){
                    echo '<form action="admin-accounts.php" method="POST">';
                        echo '<tr>';
                            echo '<th scope="row">'.$id. '</th>';
                            echo '<td><input type=text name="first_name" value="'.$first_name. '"></td>';
                            echo '<td><input type=text name="last_name" value="'.$last_name. '"></td>';
                            echo '<td><input type=text name="email" value='.$email. '></td>';
                            echo '<td><input type=text name="password" value=""></td>';
                            echo '<td><input type=text name="address" value="'.$address. '"></td>';
                            echo '<td>'.accountTypeDropdown($type). '</td>';
                            echo '<td>'.blockedStatusDropdown($is_blocked). '</td>';
                            echo '<input type="hidden" name="id" value='.$id.'/>';
                            echo '<input type="hidden" name="edit"/>'; //hidden input used to distinguish between which submit form was used
                            echo '<td><button type="submit">Save</button></td>';
                            echo '<td><button><a href="admin-accounts.php?delete='.$id.'">Delete</button></td>';
                        echo '</tr>';
                    echo '</form>';
                
                }else{
                    echo '<tr>';
                    echo '<th scope="row">'.$id. '</th>';
                    echo '<td>'.$first_name. '</td>';
                    echo '<td>'.$last_name. '</td>';
                    echo '<td>'.$email. '</td>';
                    echo '<td>'.str_repeat('&#9679;',8). '</td>';
                    echo '<td>'.$address. '</td>';
                    echo '<td>'.$type. '</td>';
                    echo '<td>'.blockedStatus($is_blocked). '</td>';
                    echo '<td><button name="update"><a href="admin-accounts.php?id='.$id.'">Edit</button></td>';
                    echo '<td><button><a href="admin-accounts.php?delete='.$id.'">Delete</button></td>';                
                    echo '</tr>';
                }
                
            }
            
}

/**
 * Rami Chaouki
 * A function that displays an empty row to add a new user
 */
function displayAddAccount($conn){
                    $table='users';
                    echo '<form action="admin-accounts.php" method="POST">';
                        echo '<tr>';
                            echo '<th class=text-danger scope="row">'.getNextIdUser($conn). '</th>';
                            echo '<td><input type=text name="first_name" value=""></td>';
                            echo '<td><input type=text name="last_name" value=""></td>';
                            echo '<td><input type=text name="email" value=""></td>';
                            echo '<td><input type=text name="password" value=""></td>';
                            echo '<td><input type=text name="address" value=""></td>';
                            echo '<input type="hidden" name="add"/>'; //hidden input used to distinguish between which submit form was used
                            echo '<td>'.accountTypeDropdown(). '</td>';
                            echo '<td>'.blockedStatusDropdown(). '</td>';
                            echo '<td><button name="add" type="submit">Add</button></td>';
                            echo '<td></td>';
                        echo '</tr>';
                    echo '</form>';
}

/**
 * Rami Chaouki
 * A function that gets the last user id from the users table
 * TODO: accurately predice what the next user id will be 
 */
function getNextIdUser($conn){
    $sql = 'select id from users where id=(select max(id) from users)';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if(empty($row)){return 1;};
            return $row[0]+1;
}

/**
 * Rami Chaouki
 * A function that gets the last postcard id from the postcards table
 */
function getNextIdPostcard($conn){
    $sql = 'select id from postcards where id=(select max(id) from postcards)';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if(empty($row)){return 1;};
            return $row[0]+1;
}

/**
 * Rami Chaouki
 * A dropdown list that gives the choice between customers and admins. This function is called in the displayUserRows and displayAddAccount
 */
function accountTypeDropdown($type='customer'){
    if($type==='admin'){
        return "<select name='type' id='select_type'><option value='admin'>admin</option><option value='customer'>customer</option></select>";
    }
    else{
        return "<select name='type' id='select_type'><option value='customer'>customer</option><option value='admin'>admin</option></select>";
    }
}

/**
 * Rami Chaouki
 * A function that displays if user is blocked or not by converting the 1 and 0 from the database to 'yes' and 'no' 
 */
function blockedStatus($is_blocked){
    if($is_blocked==1){
        return 'yes';
    }else{
        return 'no';
    }
}


/**
 * Rami Chaouki
 * A dropdown list that gives the choice between yes and no to know whether account is blocked or not. If the account was blocked, the first option in the dropdown will be 'yes', if not blocked the first option will be 'no'. This function is called in the displayUserRows and displayAddAccount
 */
function blockedStatusDropdown($is_blocked=0){
    if($is_blocked===1){
        return "<select name='is_blocked' id='select_blocked_status'><option value=1>yes</option><option value=0>no</option></select>";
    }
    else{
        return "<select name='is_blocked' id='select_blocked_status'><option value=0>no</option><option value=1>yes</option></select>";
    }
}

/**
 * Rami Chaouki
 * A CRUD function that adds a user
 */
function addUser($conn,$first_name,$last_name,$email,$password,$address,$type,$is_blocked){
    $sql='insert into users (first_name, last_name, email, password, address, type, is_blocked) 
    values (?,?,?,?,?,?,?);';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ssssssi",$first_name,$last_name,$email,$password,$address,$type,$is_blocked);
    $stmt->execute();
    

}

/**
 * Rami Chaouki
 * A CRUD function that edits a user
 */
function editUser($conn,$id,$first_name,$last_name,$email,$password,$address,$type,$is_blocked){
    //if no changes are made to the password
    if(empty($password)){
        $sql='update users set first_name=?, last_name=?, email=?, address=?, type=?, is_blocked=? where id=?';
        $stmt=$conn->prepare($sql);
        // echo var_dump(get_defined_vars());
        $stmt->bind_param('sssssii',$first_name,$last_name,$email,$address,$type,$is_blocked,$id);
        $stmt->execute();
    }else{
        $sql='update users set first_name=?, last_name=?, email=?, password=?, address=?, type=?, is_blocked=? where id=?';
        $stmt=$conn->prepare($sql);
        $stmt->bind_param('ssssssii',$first_name,$last_name,$email,$password,$address,$type,$is_blocked,$id);
        $stmt->execute();
    }
}

/**
 * Rami Chaouki
 * A CRUD function that deletes a user
 */
function deleteUser($conn,$id){
    $sql='delete from users where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
}

/**
 * Rami Chaouki
 * A function that displays rows of all the postcards at homing_pigeon that is editable and deletable.
 * It interfaces with the server's directory through <input type=file ..
 */
function displayPostcardRows($conn,$imgDir){

    // load script to allow a preview of upload
    echo previewCardJS();

    $sql='select * from postcards';
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($rows as $row) {
        extract($row);
        if(isset($_GET['id']) &&$id===$_GET['id']){
            echo '<form enctype="multipart/form-data" action="admin-postcard.php" method="POST">';
                echo '<tr>';
                    echo '<th scope="row">'.$id. '</th>';
                    echo '<td><input type=text name="name" value="'.$name. '"></td>';
                    echo '<td><img id="front_output"/>';
                    echo '<input type="file" name="front_upload" id="FrontUpload" onchange="previewCard(event)" accept=".png,.PNG"></td>';
                    echo '<td><img id="back_output"/>';
                    echo '<input type="file" name="back_upload" id="BackUpload" onchange="previewCard(event)" accept=".png,.PNG"></td>';
                    echo '<td><input type=text name="description" value="'.$description. '"></td>';
                    echo '<td><input type=text name="price" value="'.$price. '"></td>';
                    echo '<td><input type=text name="artist" value="'.$artist. '"></td>';
                    echo '<input type="hidden" name="id" value='.$id.'/>';
                    echo '<input type="hidden" name="edit"/>'; //hidden input used to distinguish between which submit form was used
                    echo '<td><button type="submit">Save</button></td>';
                    echo '<td><button><a href="admin-postcard.php?delete='.$id.'">Delete</button></td>';
                echo '</tr>';
            echo '</form>';
        
        }else{
            echo '<tr>';
            echo '<th scope="row">'.$id. '</th>';
            echo '<td>'.$name. '</td>';
            echo '<td><img src="'.$imgDir.'/artist_'.$artist.'\\recto_'.$id.'.png" width=200px height=130px></td>';
            echo '<td><img src="'.$imgDir.'/artist_'.$artist.'\\verso_'.$id.'.png" width=200px height=130px style="border: 1px solid black;"></td>';
            echo '<td>'.$description.'</td>';
            echo '<td>'.$price. '</td>';
            echo '<td>'.$artist. '</td>';
            echo '<td><button name="update"><a href="admin-postcard.php?id='.$id.'">Edit</button></td>';
            echo '<td><button><a href="admin-postcard.php?delete='.$id.'&artist='.$artist.'">Delete</button></td>';                
            echo '</tr>';
        }
        
    }
            

}

/**
 * Rami Chaouki
 * A function that displays an empty row to add a new user
 */
function displayAddPostcard($conn){
    $table='postcards';
    echo '<form enctype="multipart/form-data" action="admin-postcard.php" method="POST">';
                echo '<tr>';
                    echo '<th scope="row">'.getNextIdPostcard($conn). '</th>';
                    echo '<td><input type=text name="name" value=""></td>';
                    echo '<td><img id="front_output"/>';
                    echo '<input type="file" name="front_upload" id="FrontUpload" onchange="previewCard(event)" accept=".png,.PNG"></td>';
                    echo '<td><img id="back_output"/>';
                    echo '<input type="file" name="back_upload" id="BackUpload" onchange="previewCard(event)" accept=".png,.PNG"></td>';
                    echo '<td><input type=text name="description" value=""></td>';
                    echo '<td><input type=text name="price" value=""></td>';
                    echo '<td><input type=text name="artist" value=""></td>';
                    echo '<input type="hidden" name="add"/>'; //hidden input used to distinguish between which submit form was used
                    echo '<td><button type="submit">Add</button></td>';
                echo '</tr>';
            echo '</form>';
}

/**
 * Rami Chaouki
 * returns a javascript function that checks when a file has been inputed into <input type=file> and previews the file client-side
 */
function previewCardJS(){
    return 
        "<script>
        var previewCard = function(event) {
        var outputTo;
            if(event.target.id=='FrontUpload')
            {outputTo='front_output';
            }else
            {outputTo='back_output'}
            ;
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById(outputTo);
            console.log(outputTo)
            output.style.height='130px';
            output.style.width='200px';
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
        };
    </script>"
    ;

}

/**
 * Rami Chaouki
 * A crud function that adds a postcard to the database
 */
function addPostcard($conn,$name,$description,$price,$artist,$table='postcards'){
    $sql='insert into postcards (name,description,price,artist) values (?,?,?,?);';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssds', $name,$description,$price,$artist);
    $stmt->execute();
    return getNextIdPostcard($conn)-1;
}

/**
 * Rami Chaouki
 * A crud function that edits a postcard in the database
 */
function editPostcard($conn,$id,$name,$description,$price,$artist){
    $sql='update postcards set name=?, description=?,price=?, artist=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssdsi',$name,$description,$price,$artist,$id);
    $stmt->execute();
}

/**
 * Rami Chaouki
 * A crud function that saves into an associative array the row that is about to be edited
 */
function getPreEditInfo($conn,$id){
    $sql='select * from postcards where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result=$stmt->get_result();
    $row=$result->fetch_assoc();
    return($row);
}

/**
 * Rami Chaouki
 * A crud function that deletes a postcard from the database. It also interfaces with the server directory and removes the files associated to this postcard
 */
function deletePostcard($conn,$id,$artist,$localImgDir){
    $sql='Delete from postcards where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();

    deletePostcardFromDir($id,$artist,$localImgDir);
}

/**
 * Rami Chaouki
 * A function to delete the files from the server's directory
 */
function deletePostcardFromDir($id,$artist,$localImgDir){
    $artistDir=artistDir($localImgDir,$artist);
    $rectoPath=$artistDir.'recto_'.$id.'.png';
    $versoPath=$artistDir.'verso_'.$id.'.png';
    //deletes postcard files
    try{
    unlink($rectoPath);
    unlink($versoPath);}
    finally{
    //checks if directory is empty, deletes it if it is.
    deleteEmptyDir($localImgDir,$artist);
}
}

/**
 * Rami Chaouki
 * A function that when called deletes an artist's directory if it's empty
 */
function deleteEmptyDir($localImgDir,$artist){
    $artistDir=artistDir($localImgDir,$artist);
    //Detects if folder is empty -- scandir will return an array of size 3 or more if not empty
    if(count(scandir($artistDir))<3){
        //Deletes directory
        rmdir($artistDir);
    }

}

/**
 * Rami Chaouki
 * A function that stores upload into proper directory
 */
function storeImage($imgDir,$file,$id,$artist,$side){
    // echo $imgDir.$id.$artist.$side;
    if(empty($file)){return;}
    //Picks the right directory for the artist, if no directory is found, it creates one
    $artistDir=artistDir($imgDir,$artist);
    if($side=='recto'){
        $file['front_upload']['name']='recto_'.$id.'.png';
        $artistDir=$artistDir.basename($file['front_upload']['name']);
        move_uploaded_file($file['front_upload']['tmp_name'],$artistDir);
    }else{
        $file['back_upload']['name']='verso_'.$id.'.png';
        $artistDir=$artistDir.basename($file['back_upload']['name']);
        move_uploaded_file($file['back_upload']['tmp_name'],$artistDir);
    }
    
}

/**
 * Rami Chaouki
 * A function that answers to the edge-case where you edit the artist name but do not add new postcards. This function will copy the postcards of the old artist and add them to the folder of the new one
 */
function portImages($localImgDir,$id,$oldArtist,$newArtist){
    $oldRecto=artistDir($localImgDir,$oldArtist).'recto_'.$id.'.png';
    $oldVerso=artistDir($localImgDir,$oldArtist).'verso_'.$id.'.png';
    
    $newArtistDir=artistDir($localImgDir,$newArtist);
    // echo($oldRecto).'<br>';
    // echo($newArtistDir);
    if(!is_dir($newArtistDir)){
        mkdir($newArtistDir);
    }
    $newArtistDirRecto=$newArtistDir.basename('recto_'.$id.'.png');
    $newArtistDirVerso=$newArtistDir.basename('verso_'.$id.'.png');
    copy($oldRecto,$newArtistDirRecto);
    copy($oldVerso,$newArtistDirVerso);
}

/**
 * Rami Chaouki
 * A function that picks the right directory for given an artist, if no directory is found, it creates one
 */
function artistDir($localImgDir,$artist){
    //puts lowercase on name for standardization
    $artist=strtolower($artist);
    //since is_dir uses local directory C:// (not localhost), I added a localImgDir variable that is in the config file
    $dirName=$localImgDir.'/artist_'.$artist.'/';
    //checks if directory exists, if not, creates one
    if(!is_dir($dirName)){
        mkdir($dirName);
    }
    return $dirName;
}

 /** Alina Gotcherian
 * A function that checks if a user is an admin
 * returns a boolean
 * parameters: none
 */
function is_admin()
{
  require "config/db_config.php";
  // Check if there is a user id
  $userid = $_SESSION['id'];
  $sql = "SELECT *
          FROM users
          WHERE id='$userid' ";
  $result = mysqli_query($conn, $sql);
  $numofrows = mysqli_num_rows($result);
  if ($numofrows >= 1) {
    if (mysqli_fetch_array($result)['type'] == "admin") return true;
  } else {
    return false;
  }
}
