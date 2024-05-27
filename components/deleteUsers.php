<?
include "header.php";


$user = $link->query("SELECT * FROM users WHERE status = 'user' ");
?>
<section class="section posts">
            <?php foreach ($user as $key => $value):?>
                <div class="post">
                    <h3>Логин: <?= $value['login'] ?></h3>
                </div>
                <div class="post">
                    <h3>Email: <?= $value['email'] ?></h3>
                </div>
                <form action="../action/deleteUsers.php" method="post">
                        <input type="hidden" value="<?= $value['id'] ?>" name="id">
                        <button name="deleteUsers">
                            Удалить
                        </button>
                    </form>
            <?php endforeach; ?>
        </section>