<?
include "header.php";
// if(empty($_SESSION['user']['login'])){
//     $id = $_SESSION['admin']['login'];
//   }
//   else{
//     $id = $_SESSION['user']['login'];
//   }
$foto = $link->query("SELECT * FROM photo");
?>
<section class="section posts">
            <?php foreach ($foto as $key => $value):?>
                <div class="post">
                    <div class="img"><img class="img" src="../assets/img/<?= $value['img'] ?>" alt="Собака))"></div>
                    <h3><a href=""><?= $value['name'] ?></a></h3>
                </div>
                <form action="../action/delete.php" method="post">
                        <input type="hidden" value="<?= $value['id'] ?>" name="id">
                        <button name="deletePost">
                            Удалить
                        </button>
                    </form>
            <?php endforeach; ?>
        </section>

