<?
include "header.php";
$foto = mysqli_query($link, "SELECT * FROM `photo` ORDER BY id DESC LIMIT 11");
$commen = mysqli_query($link, "SELECT * FROM `comments` ORDER BY id DESC LIMIT 5");

if(empty($_SESSION['user']['login'])){
  $id = $_SESSION['admin']['login'];
}
else{
  $id = $_SESSION['user']['login'];
}
?>
  
    <div class="telo">
      <div class="block"></div>
      <div class="block1">
        <p class="name_funcs">Залы</p>
        <div class="zali">
          <div class="block_zal">
            <a>Зал с циклорамой</a>
            <div class="zal_inf">
            <img class="inf" src="./assets/img/chikl.jpg">
          </div>
          <a class="opis">специальный фон с плавным переходом вертикальной плоскости в горизонтальную</a>
          </div>
          

          <div class="block_zal">
          <a>loft</a>
          <div class="zal_inf">
          <img class="inf" src="./assets/img/loft.jpg">
          </div>
          <a class="opis">особая стилистика, применяемая для фотостудии</a>
          </div>

          <div class="block_zal">
          <a>Детский</a>
          <div class="zal_inf">
          <img class="inf" src="./assets/img/detskay.jpeg">
          </div>
          <a class="opis">место где создаются маленькие приключения и исполняются мечты</a>
          </div>

        </div>

        <p class="name_funcs">Фотографии</p>
        <div class="grid-wrapper">
<?php 
foreach ($foto as $key => $value):?>
    <?$size = getimagesize ("./assets/img_gallery/{$value['img']}");
$img_height = $size[1];
$img_width = $size[0];?>
                <!-- <div class="post">
                    <div class="img"><img class="img" src="../assets/img/<?= $value['img'] ?>" alt=""></div>
                    <h3><a href=""><?= $value['name'] ?></a></h3>
                </div>
                <form action="../action/delete.php" method="post">
                        <input type="hidden" value="<?= $value['id'] ?>" name="id">
                        <button name="deletePost">
                            Удалить
                        </button>
                    </form> -->
                    <?if ($img_height > $img_width):?>
                        <div class="tall">
                            <img src="../assets/img_gallery/<?=$value['img']?>" alt="" />
                        </div>
                        <?
                        elseif($img_width < 1500):?>
                        <div class="wide">
                            <img src="../assets/img_gallery/<?=$value['img']?>" alt="" />
                        </div>
                        <?
                        elseif($img_width > 3000):?>
                        <div class="big">
                            <img src="../assets/img_gallery/<?=$value['img']?>" alt="" />
                        </div>
                        <?
                        elseif(1500<$img_width AND $img_width < 3000):?>
                        <div>
                            <img src="../assets/img_gallery/<?=$value['img']?>" alt="" />
                        </div>
                        <?
                        endif;
                        ?>
                    
            <?php endforeach; ?>
</div>
<div class="container">

  <a href="./components/gallery.php" class="button type--A">
    <div class="button__line"></div>
    <div class="button__line"></div>
    <span class="button__text">Ещё</span>
    <div class="button__drow1"></div>
    <div class="button__drow2"></div>
  </a>
    

</div>


<p class="name_funcs">Бронирование</p>


<div class="center" id="bron">
<?include_once './bron.php';
// $size = getimagesize ("./assets/img/6371254008418562609426200.jpg");
// echo "< img src=\"pictures/celebrities_476.jpg\" {$size[1]}>";
?>
</div>

<p class="name_funcs">Оставить коментарий</p>

        <div class="comment">
          
        <form id="forma" action="./action/comment.php?id=<?=$id;?>" method="POST">
  <div>
    <textarea id="comment" name="comment" cols="50" rows="4" required></textarea><br>
  </div>
  <div>
  <div class="position">

    <!--start button, nothing above this is necessary -->
    <div class="svg-wrapper">
      <svg height="40" width="150" xmlns="http://www.w3.org/2000/svg">
        <rect id="shape" height="40" width="150" />
        <div id="text">
          
          <a name='com' onclick="document.getElementById('forma').submit();"><span class="spot"></span>Отправить</a>
        </div>
      </svg>
    </div>
  </div>
  </div>
</form>
        </div>

        <p class="name_funcs">Коментарии</p>


<div class="card">
<div class="products">
<div class="product active" product-id="1" product-color="#A5AAAE">
      
      <h1 class="title">The Sneaky Mouse</h1>
      <p class="description">Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
    </div>
  <?
  $counter = 1;
  foreach ($commen as $key => $value):
    $counter++;
  ?>
    <div class="product" product-id="<?=$counter?>">
      
      <h1 class="title"><?=$value['users_id']?></h1>
      <p class="description"><?=$value['description']?></p>
    </div>
    
    <!-- <div class="product" product-id="4" product-color="#ED8D1F">
      
      <h1 class="title">The Cunning Fox</h1>
      <p class="description">Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
    </div>
    <div class="product" product-id="5" product-color="#C4C8CB">
      
      <h1 class="title">The Jumpy Rabbit</h1>
      <p class="description">Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
    </div> -->
  <?
  endforeach;
  ?>
  </div>
  <div class="footer"><a class="btn_slider" id="prev" href="#" ripple="" ripple-color="#666666">Prev</a><a class="btn_slider" id="next" href="#" ripple="" ripple-color="#666666">Next</a></div>
</div>







        </div>
      </div>

      





























    
  </body>
  <script type="text/javascript" src="/js/img.js"></script>
  <script type="text/javascript" src="/js/slider.js"></script>
</html>
