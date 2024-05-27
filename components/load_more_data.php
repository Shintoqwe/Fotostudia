<?php

require_once 'config.php';

$limit = 5;
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0;

$query = "SELECT * FROM photo ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

if ($result->num_rows > 0) {?>
    <?
    foreach ($result as $key => $value) {?>
    
        <?$size = getimagesize ("../assets/img_gallery/{$value['img']}");
        $img_height = $size[1];
        $img_width = $size[0];?>
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
                                
    <?};?>
<?};
?>
