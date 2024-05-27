<?php 
    include "./core.php";
    if(isset($_POST['userslogin'])){
        $password = md5($_POST['password']."sadgfds");
        $users = $link->query("SELECT * FROM users 
            WHERE login = '{$_POST['login']}' AND password = '$password' AND status = 'user'");


        $admin = $link->query("SELECT * FROM users 
        WHERE login = '{$_POST['login']}' AND password = '$password' AND status = 'admin'");
       
        if($users->num_rows != 1 AND $admin->num_rows != 1){
            $_SESSION['errors']['login'] = "Логин или пароль не совпадают";
        }


        if($users->num_rows == 1){
            if (!isset($_SESSION['errors']['login'])){
                $user = $users->fetch_assoc();
                $_SESSION['user']['login'] = $user['id'];
                }
        }
        elseif ($admin->num_rows == 1){
            if(!isset($_SESSION['errors']['admin'])){
                $user = $admin->fetch_assoc();
                $_SESSION['admin']['login'] = $user['id'];
            }
        }
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
?>