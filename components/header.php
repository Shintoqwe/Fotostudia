<?
include "../action/core.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../assets/style/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/style/style-menu.css">
    <link rel="stylesheet" href="../assets/style/right-nav-style.css">
    <script type="text/javascript" src="/js/jquery-1.11.3.js"></script>
    <title>FotoGal</title>
  </head>
  <header>
  <input type="checkbox" id="nav-toggle" hidden>
    <!-- 
    Выдвижную панель размещаете ниже
    флажка (checkbox), но не обязательно 
    непосредственно после него, например
    можно и в конце страницы
    -->
    <nav class="nav">
        <!-- 
    Метка с именем `id` чекбокса в `for` атрибуте
    Символ Unicode 'TRIGRAM FOR HEAVEN' (U+2630)
    Пустой атрибут `onclick` используем для исправления бага в iOS < 6.0
    См: http://timpietrusky.com/advanced-checkbox-hack 
    -->
        <label for="nav-toggle" class="nav-toggle" onclick></label>
        <!-- 
    Здесь размещаете любую разметку,
    если это меню, то скорее всего неупорядоченный список <ul>
    -->
    <h2 class="logo"> 
            <a>Меню</a> 
        </h2>
        <ul>
        <?php if(isset($_SESSION['user'])):?>
          <li><a href="/index.php">Онлайн запись</a></li>
          <li><a>Услуги</a>
          <ul>
        <li><a href="./arenda.php">Аренда студии</a></li>
        <li><a href="#">Фотосессия</a></li>
        <li><a href="#">Аренда гримёрной</a></li>
        <li><a href="#">Абонемент</a></li>
      </ul>
        </li>
        <li><a>Студии</a>
        <ul>
        <li><a href="studia_chicl.php">Зал с циклорамой</a></li>
        <li><a href="studia_loft.php">loft</a></li>
        <li><a href="studia_det.php">Детский</a></li>
      </ul>
    </li>
    <li><a href="gallery.php">Галлерея</a></li>
    <li><a href="addcoment.php">Оставить коментарий/a></li>
        <li><a href="№">Контакты</a></li>
            
          <li><a href="../action/logout.php" >Выход</a></li>
        
        <?php elseif (isset($_SESSION['admin'])) :?>
          <li><a>Услуги</a>
          <ul>
        <li><a href="./arenda.php">Аренда студии</a></li>
        <li><a href="#">Фотосессия</a></li>
        <li><a href="#">Аренда гримёрной</a></li>
        <li><a href="#">Абонемент</a></li>
      </ul>
        </li>
        <li><a>Студии</a>
        <ul>
        <li><a href="studia_chicl.php">Зал с циклорамой</a></li>
        <li><a href="studia_loft.php">loft</a></li>
        <li><a href="studia_det.php">Детский</a></li>
      </ul>
    </li>
    <li><a href="gallery.php">Галлерея</a></li>
    <li><a href="addcoment.php">Оставить коментарий</a></li>
          <li><a href="../action/logout.php" >Выход</a></li>
        
        
        <li><a href="../components/addPhoto.php" >Добавить фото</a></li>
        
        
        <li><a href="../components/deletePhoto.php" >Удалить фото</a></li>
        
        
        <li><a href="../components/deleteUsers.php" >Удалить пользователя</a></li>
        
          
          
          <? else: ?>
        
            <li><a href="autolog.php" >Авторизация</a></li>
        
        
        <li><a href="autoreg.php" >Регистрация</a></li>
        
        <?php endif; ?>
        </ul>
    </nav>



    <div class="head">
      <div class="logo">
        <a href="../Index.php" class="head">Главная</a>
      </div>
      <div class="rightBox">
      
        <div class="categories">
        <!-- <p class="head">categories</p> -->
          
        </div>
        
      </div>
    </div>
  </header>
  <body>