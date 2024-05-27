<?
include "core.php";


if(isset($_POST['registr'])){
    if($_POST['password'] !== $_POST['password_conf']){
        $_SESSION['errors']['reg'] = "Пароли должны совпадать";
    }

    $users = $link->query("SELECT * FROM users 
        WHERE login = '{$_POST['login']}' OR email = '{$_POST['email']}'");

    if($users->num_rows > 0){
        $_SESSION['errors']['reg'] = "Email или логин уже используеются";
    }
    if(empty($_POST['login']) OR empty($_POST['email']) OR empty($_POST['password'])){
        $_SESSION['errors']['reg'] = "Заполните все данные";
    }
    else{
        $password = md5($_POST['password']."sadgfds");
        $res = $link->query("INSERT INTO users (`login`, `email`, `password`) 
            VALUES ('{$_POST['login']}', '{$_POST['email']}', '$password')");
    }
}

header("Location: ".$_SERVER['HTTP_REFERER']);