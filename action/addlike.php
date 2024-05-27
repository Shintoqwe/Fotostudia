<?php 
    include "core.php";
    if(isset($_POST['id'])){
        $like = $link->query("SELECT * FROM like 
            WHERE users_id = '{$_SESSION['user']['login']}' 
                AND photo_id = '{$_POST['id']}'");

        if(isset($_POST['like'])){
            if($like->num_rows == 0){
                $link->query("INSERT INTO `like` (`users_id`, `photo_id`, `status`)
                VALUES ('{$_SESSION['user']['login']}', '{$_POST['id']}', 1)");
            }else{
                $likes = $like->fetch_assoc();
                if($likes['status'] == 1){
                    $link->query("DELETE FROM like WHERE id = '{$likes['id']}'");
                }else{
                    $link->query("UPDATE like SET `status` = 1 WHERE id = '{$likes['id']}'");
                }
            }
        }
        if(isset($_POST['dislike'])){
            if($like->num_rows == 0){
                $link->query("INSERT INTO `like` (`users_id`, `photo_id`, `status`)
                VALUES ('{$_SESSION['user']['login']}', '{$_POST['id']}', 0)");
            }else{
                $likes = $like->fetch_assoc();
                if($likes['status'] == 0){
                    $link->query("DELETE FROM like WHERE id = '{$likes['id']}'");
                }else{
                    $link->query("UPDATE like SET `status` = 0 WHERE id = '{$likes['id']}'");
                }
            }
        }
    }

    
    redirect();
?>