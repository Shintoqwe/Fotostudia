<?
include "header.php";
?>

    <form action="../action/login.php" method="POST">
    <div class="login-box">
        <h1>Авторизация</h1>
        <div class="textbox">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Логин" name="login" required>
        </div>
        <div class="textbox">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Пароль" name="password" required>
        </div>
        <input type="submit" class="btn" name="userslogin" value="Войти">
    </div>
        <?php 
                    if(isset($_SESSION['errors']['login'])): ?>
                        <div>
                            <?php 
                                foreach ($_SESSION['errors'] as $key => $value):
                                    echo "<p> $value </p>";
                                endforeach;
                            ?>
                        </div>
                    <?php
                        unset($_SESSION['errors']['login']); 
                        endif;?>
    </form>
    </div>
</body>
</html>