<?
include "core.php";

        $id = $_GET['id'];

        $res = $link->query("INSERT INTO `comments`(`users_id`, `description`) VALUES ('$id', '{$_POST['comment']}')");
        $_SESSION['errors']['name'] = "Товар добавлен";
                
        
    
    header("Location: ../Index.php#bron");
?>