<? include_once './class_bron/class.db.php';
$db= new SafeMySQL();
if(isset($_POST['date_room'])){
	$mesto=$db->getRow("SELECT * FROM bron_time WHERE date=?s AND id_room=?i",$_POST['date_room'],$_POST['id_room']);
	if($mesto[$_POST['seans']]==1){echo 2;}
else{
	if(date("d.m.Y")==$_POST['date_room']){
		$yy=time()+1800;
		$rooms=$db->getRow("SELECT * FROM bron WHERE id=?i",$_POST['id_room']);
			if($rooms[$_POST['seans']]<=date("H:i",$yy)){echo 3;exit;}
	}
	if($_POST['name']=='' or $_POST['phone']==''){echo 1;exit;}	
	if($_POST['id_room']==2 or $_POST['id_room']==1){
	$db->query("UPDATE bron_time SET ".$_POST['seans']."='1' WHERE (id_room=?i or id_room=?i) AND date=?s",'1','2',$_POST['date_room']);
	}else{
	$db->query("UPDATE bron_time SET ".$_POST['seans']."='1' WHERE id_room=?i AND date=?s",$_POST['id_room'],$_POST['date_room']);
	}
	$vrem=$db->getRow("SELECT * FROM bron WHERE id=?i",$_POST['id_room']);
mail("kvestkoma@gmail.com ","Бронирование","Комната: ".$vrem['name_room']."\nДата: ".$_POST['date_room']." в ".$vrem[$_POST['seans']]."\nТелефон: ".$_POST['phone']."\nИмя: ".$_POST['name'], "From: kvestkoma@gmail.com  <КОМА>");	
	echo getRooms($_POST['date_room'],$db);
}
	exit;
}

if(isset($_POST['refrash'])){
	echo getRooms(date("d.m.Y"),$db);
	exit;
}

if(isset($_POST['days_re'])){
	echo getRooms($_POST['days_re'],$db);
	exit;
}
?>
<script>
function bron(a,b,c){
	name=$('#name').val();
	phone=$('#phone').val();
	$.post('/bron.php','date_room='+a+'&seans='+b+'&id_room='+c+'&phone='+phone+'&name='+name,function(data){
		switch(data){
			case'1':alert('Ошибка введите имя или телефон!');break;
			case'2':alert('Время уже занто!');break;
			case'3':alert('Вы выбрали уже закрытое время!');break;
			default:$('#rooms_table').html(data);hide_z();
		}
	});
}

function refrash(){
	$.post('/bron.php','refrash=1',function(data){
		switch(data){
			case'1':alert('Ошибка!');break;
			default:$('#rooms_table').html(data);
		}
	});
}

setTimeout('refrash();',200);

function days(a){
	$.post('/bron.php','days_re='+a,function(data){
		switch(data){
			case'1':alert('Ошибка!');break;
			default:$('#rooms_table').html(data);
		}
	});
}

function bron_okno(a,b,c,d,e){
	$('#okno_br').show(500);
	$('#date_z').val(a+' в '+e);
	$('#name_room').val(d);
	$('#but_z').attr('onclick','bron(\''+a+'\',\''+b+'\',\''+c+'\')');
	
}

function hide_z(){
	$('#okno_br').hide(500);
	
}
</script>
<?
echo '<div class="okno_all" style="display:none;" id="okno_br">
<div class="okno_one">
<div class="krest" onClick="hide_z();">x</div>
<div class="polay">
<input type="text" id="date_z" disabled="disabled" value=""/>
<input type="text" id="name_room" disabled="disabled" value=""/>
<input type="text" id="name" placeholder="Ваше имя"/>
<input type="text" id="phone" placeholder="Ваш телефон"/>
<input type="button" id="but_z" value="ЗАБРОНИРОВАТЬ"/>
</div>
</div>
</div>';
echo '<div style="width:75%;margin:auto;color:#fff;font-size:15px;">
';
echo '<table class="bron_room" id="rooms_table" style="margin-bottom: 30px;">';
$date=date("d.m.Y");
echo getRooms($date,$db);
echo '</table>';
echo '<div style="margin-top: 20px;width:25%;float:left;font-size: 14px;">
<div style="width: 40%;
    float: left;
    border: 1px solid #fff;
    height: 20px;
    margin: 0px;
    text-align: center;">свободно</div>
<div class="fiol" style="width: 40%;
    float: right;
    border: 0px solid #fff;
    height: 20px;
    padding: 2px;
    margin: 0px;
    text-align: center;">занято</div>
</div>
<div class="clr"></div>';
echo'</div>';
?>