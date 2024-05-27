<?
include "../action/core.php";
include "header.php";
if(empty($_SESSION['user']['login'])){
  $id = $_SESSION['admin']['login'];
}
else{
  $id = $_SESSION['user']['login'];
}
?>


<form action="../action/addPhoto.php?id=<?=$id;?>" method="POST" enctype="multipart/form-data">
<div class="upload-box">
        <h1>Добавление фотографии</h1>
        <div class="textbox">
            <i class="fas fa-file-image"></i>
        </div>
        <div class="textbox">
            <i class="fas fa-file-upload"></i>
            <input type="file" name="img" required>
        </div>
        <input type="submit" class="btn" name="addPhoto" value="Загрузить фотографию">
    </div>
  <?php 
                    if(isset($_SESSION['errors']['name'])): ?>
                        <div>
                            <?php 
                                foreach ($_SESSION['errors'] as $key => $value):
                                    echo "<p> $value </p>";
                                endforeach;
                            ?>
                        </div>
                    <?php
                        unset($_SESSION['errors']['name']); 
                        endif;?>
</form>