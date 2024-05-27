<?

include "action/core.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./assets/style/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/style/style-menu.css">
    <link rel="stylesheet" href="./assets/style/right-nav-style.css">
    <link rel="stylesheet" type="text/css" href="./assets/style/style.css">
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
          <li><a href="/index.php#bron">Онлайн запись</a></li>
          <li><a>Услуги</a>
          <ul>
        <li><a href="/components/arenda.php">Аренда студии</a></li>
        <li><a href="#">Фотосессия</a></li>
        <li><a href="#">Аренда гримёрной</a></li>
        <li><a href="#">Абонемент</a></li>
      </ul>
        </li>
        <li><a>Студии</a>
        <ul>
        <li><a href="./components/studia_chicl.php">Зал с циклорамой</a></li>
        <li><a href="./components/studia_loft.php">loft</a></li>
        <li><a href="./components/studia_det.php">Детский</a></li>
      </ul>
    </li>
    <li><a href="./components/gallery.php">Галерея</a></li>
    <li><a href="./components/addcoment.php">Оставить коментарий</a></li>
        <li><a href="№">Контакты</a></li>
          
          <li><a href="./action/logout.php">выход</a></li>
        
        <?php elseif (isset($_SESSION['admin'])) :?>
          <li><a href="/index.php#bron">Онлайн запись</a></li>
          <li><a>Услуги</a>
          <ul>
        <li><a href="/components/arenda.php">Аренда студии</a></li>
        <li><a href="#">Фотосессия</a></li>
        <li><a href="#">Аренда гримёрной</a></li>
        <li><a href="#">Абонемент</a></li>
      </ul>
        </li>
        <li><a>Студии</a>
        <ul>
        <li><a href="./components/studia_chicl.php">Зал с циклорамой</a></li>
        <li><a href="./components/studia_loft.php">loft</a></li>
        <li><a href="./components/studia_det.php">Детский</a></li>
      </ul>
    </li>
    <li><a href="./components/gallery.php">Галерея</a></li>
    <li><a href="./components/addcoment.php">Оставить коментарий</a></li>
          
        
        
        <li><a href="./components/addPhoto.php">добавить фото</a></li>
        
        
        <li><a href="./components/deletePhoto.php">удалить фото</a></li>
        
        
        <li><a href="./components/deleteUsers.php">удалить пользователя</a></li>
        <li><a href="./action/logout.php">выход</a></li>
        <?php else: ?>
        
        <li><a href="./components/autolog.php">Авторизация</a></li>
        <li><a href="./components/autoreg.php">Регистрация</a></li>
        
        <?php endif; ?> 
            
        </ul>
    </nav>
    <div class="head">
      <div class="logo">
        <a href="Index.php" class="head">Главная</a>
      </div>
    </div>
  </header>
  <body>