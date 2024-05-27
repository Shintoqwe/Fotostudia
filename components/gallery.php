<?
include "header.php";


$foto = $link->query("SELECT * FROM photo");





?>
<div class="grid-wrapper">
<?php foreach ($foto as $key => $value):?>
    <?$size = getimagesize ("../assets/img_gallery/{$value['img']}");
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

<!-- <div class="grid-wrapper">
	<div class="">
		<img src="https://images.unsplash.com/photo-1541845157-a6d2d100c931?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=1350&amp;q=80" alt="" />
	</div>
	<div>
		<img src="https://images.unsplash.com/photo-1588282322673-c31965a75c3e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=1351&amp;q=80" alt="" />
	</div>
	<div class="tall">
		<img src="https://images.unsplash.com/photo-1588117472013-59bb13edafec?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=500&amp;q=60" alt="">
	</div>
	<div class="wide">
		<img src="https://images.unsplash.com/photo-1587588354456-ae376af71a25?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="" />
	</div>
	<div>
		<img src=" https://images.unsplash.com/photo-1558980663-3685c1d673c4?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=1000&amp;q=60" alt="" />
	</div>
	<div class="tall">
		<img src="https://images.unsplash.com/photo-1588499756884-d72584d84df5?ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=2134&amp;q=80" alt="" />
	</div>
	<div class="big">
		<img src="https://images.unsplash.com/photo-1588492885706-b8917f06df77?ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1951&amp;q=80" alt="" />
	</div>
</div> -->