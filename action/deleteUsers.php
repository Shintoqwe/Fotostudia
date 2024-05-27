<?php 
    include "core.php";
    if(isset($_POST['deleteUsers'])){
        $link->query("DELETE FROM users WHERE id = '{$_POST['id']}'");
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
?>