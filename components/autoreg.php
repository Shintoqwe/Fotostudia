<?
include "header.php";
?>
  </header>
    <form action="../action/reg.php" method="POST">
    <div class="register-box">
        <h1>Регистрация</h1>
        <div class="textbox">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Логин" name="login" required>
        </div>
        <div class="textbox">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email" required>
        </div>
        <div class="textbox">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Пароль" name="password" required>
        </div>
        <div class="textbox">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Подтвердите пароль" name="password_conf" required>
        </div>
        <input type="submit" class="btn" name="registr" value="Зарегистрироваться">
    </div>
        <?php 
                    if(isset($_SESSION['errors']['reg'])): ?>
                        <div>
                            <?php 
                                foreach ($_SESSION['errors'] as $key => $value):
                                    echo "<p> $value </p>";
                                endforeach;
                            ?>
                        </div>
                    <?php
                        unset($_SESSION['errors']['reg']); 
                        endif;?>
    </form>
    </div>
</body>
</html>