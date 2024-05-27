<?
include "core.php";

    if(isset($_POST['addPhoto'])){
        $id = $_GET['id'];
        // var_dump($id);
        // $users = $link->query("SELECT * FROM products 
        //     WHERE name = '{$_POST['name']}'");
        $Img = $_FILES['img'];
        // if($users->num_rows > 0){
        //     $_SESSION['errors']['name'] = "Название уже занято";
        // }
        if(empty($_POST['name']) OR empty($Img)){
            $_SESSION['errors']['name'] = "Заполните все данные";
        }
        if("image" == substr($Img['type'], 0, 5)){ 
            
            $nameImg = uniqid().".".substr($Img['type'], 6);
            move_uploaded_file($Img['tmp_name'], "../assets/img_gallery/".$nameImg);
            $res = $link->query("INSERT INTO photo (`users_id`, `img`) 
                VALUES ('$id', '$nameImg')");
                $_SESSION['errors']['name'] = "Товар добавлен";
                
        }
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
?>