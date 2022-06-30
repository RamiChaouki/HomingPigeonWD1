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
                    echo '<form action="admin-accounts.php" method="POST">';
                        echo '<tr>';
                            echo '<th class=text-danger scope="row">'.getNextId($conn). '</th>';
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


function getNextId($conn){
    $sql = 'select id from users where id=(select max(id) from users)';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
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
        echo var_dump(get_defined_vars());
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