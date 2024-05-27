<?php 
    include "core.php";
    if(isset($_POST['deletePost'])){
        $link->query("DELETE FROM photo WHERE id = '{$_POST['id']}'");
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
?>