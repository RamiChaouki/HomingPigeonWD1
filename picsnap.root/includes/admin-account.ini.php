<?php
            include_once('./includes/config/db_config.php');
            include_once('functions.inc.php');
            if(isset($_POST['add'])){
                            extract($_POST);
                            addUser($conn,$first_name,$last_name,$email,$password,$address,$type,$is_blocked);
                            header('location: ../picsnap.root/admin-accounts.php');
                            exit();
            }
            
            if(isset($_POST['edit'])){
                extract($_POST);
                editUser($conn,$id,$first_name,$last_name,$email,$password,$address,$type,$is_blocked);
                header('location: ../picsnap.root/admin-accounts.php');
                exit();
            }
            
            if(isset($_GET['delete'])){
                deleteUser($conn,$_GET['delete']);
                header('location: ../picsnap.root/admin-accounts.php');
                exit();
            }

            displayUserRows($conn);
            displayAddAccount($conn);
            
           
            