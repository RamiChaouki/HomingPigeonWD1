<?php
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


function getNextIdUser($conn){
    $sql = 'select id from users where id=(select max(id) from users)';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if(empty($row)){return 1;};
            return $row[0]+1;
}

function getNextIdPostcard($conn){
    $sql = 'select id from postcards where id=(select max(id) from postcards)';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if(empty($row)){return 1;};
            return $row[0]+1;
}

function accountTypeDropdown($type='customer'){
    if($type==='admin'){
        return "<select name='type' id='select_type'><option value='admin'>admin</option><option value='customer'>customer</option></select>";
    }
    else{
        return "<select name='type' id='select_type'><option value='customer'>customer</option><option value='admin'>admin</option></select>";
    }
}

function blockedStatus($is_blocked){
    if($is_blocked==1){
        return 'yes';
    }else{
        return 'no';
    }
}

function blockedStatusDropdown($is_blocked=0){
    if($is_blocked===1){
        return "<select name='is_blocked' id='select_blocked_status'><option value=1>yes</option><option value=0>no</option></select>";
    }
    else{
        return "<select name='is_blocked' id='select_blocked_status'><option value=0>no</option><option value=1>yes</option></select>";
    }
}

function addUser($conn,$first_name,$last_name,$email,$password,$address,$type,$is_blocked){
    $sql='insert into users (first_name, last_name, email, password, address, type, is_blocked) 
    values (?,?,?,?,?,?,?);';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ssssssi",$first_name,$last_name,$email,$password,$address,$type,$is_blocked);
    $stmt->execute();
    

}

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

function deleteUser($conn,$id){
    $sql='delete from users where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
}

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

function addPostcard($conn,$name,$description,$price,$artist,$table='postcards'){
    $sql='insert into postcards (name,description,price,artist) values (?,?,?,?);';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssds', $name,$description,$price,$artist);
    $stmt->execute();
    return getNextIdPostcard($conn)-1;
}

function editPostcard($conn,$id,$name,$description,$price,$artist){
    $sql='update postcards set name=?, description=?,price=?, artist=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssdsi',$name,$description,$price,$artist,$id);
    $stmt->execute();
}
function getPreEditInfo($conn,$id){
    $sql='select * from postcards where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result=$stmt->get_result();
    $row=$result->fetch_assoc();
    return($row);
}

function deletePostcard($conn,$id,$artist,$localImgDir){
    $sql='Delete from postcards where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();

    deletePostcardFromDir($id,$artist,$localImgDir);
}

function deletePostcardFromDir($id,$artist,$localImgDir){
    $artistDir=artistDir($localImgDir,$artist);
    $rectoPath=$artistDir.'recto_'.$id.'.png';
    $versoPath=$artistDir.'verso_'.$id.'.png';
    //deletes postcard files
    unlink($rectoPath);
    unlink($versoPath);
}

function deleteEmptyDir($localImgDir,$artist){
    $artistDir=artistDir($localImgDir,$artist);
    //Detects if folder is empty -- scandir will return an array of size 3 or more if not empty
    if(count(scandir($artistDir))<3){
        //Deletes directory
        rmdir($artistDir);
    }

}

//Stores upload into proper directory
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

//answers to the edge-case where you edit the artist name but do not add new postcards. This function will copy the postcards of the old artist and add them to the folder of the new one
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

//Picks the right directory for the artist, if no directory is found, it creates one
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

function getCards(){
    $projDir= getcwd();
    $imgDir= getcwd().'\\images\\';
    $subDirs=scandir($imgDir,1);
    foreach($subDirs as $subDir){
        if($subDir!='.'&& $subDir!='..'){
            chdir($imgDir.$subDir);
            // echo getcwd();
            // $files=glob("*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}",GLOB_BRACE);
            // echo var_dump($files);
            // return $files;
        //     foreach ($files as $file) {
        //     echo '<img src="images/" '. $file.' style="height: 200px; width: 200px;/>';
        // }
        }
    }

    // chdir($projDir);
    
    
    // echo $files;
}