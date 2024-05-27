<? include_once './class_bron/class.db.php';
$db= new SafeMySQL();
if(isset($_POST['date_room'])){
	$mesto=$db->getRow("SELECT * FROM bron_time WHERE date=?s AND id_room=?i",$_POST['date_room'],$_POST['id_room']);
	if($mesto[$_POST['seans']]==1){
		if($_POST['id_room']==2 or $_POST['id_room']==1){
	$db->query("UPDATE bron_time SET ".$_POST['seans']."='0' WHERE (id_room=?i or id_room=?i) AND date=?s",'1','2',$_POST['date_room']);
	}else{
	$db->query("UPDATE bron_time SET ".$_POST['seans']."='0' WHERE id_room=?i AND date=?s",$_POST['id_room'],$_POST['date_room']);
	}		
	echo getRooms_admin($_POST['date_room'],$db);exit;}
else{	
	$db->query("UPDATE bron_time SET ".$_POST['seans']."='1' WHERE id_room=?i AND date=?s",$_POST['id_room'],$_POST['date_room']);
		
	echo getRooms_admin($_POST['date_room'],$db);
}
	exit;
}

if(isset($_POST['refrash'])){
	echo getRooms_admin(date("d.m.Y"),$db);
	exit;
}

if(isset($_POST['days_re'])){
	echo getRooms_admin($_POST['days_re'],$db);
	exit;
}
?>
<script>
function bron(a,b,c){
	name=$('#name').val();
	phone=$('#phone').val();
	$.post('/admin_bron.php','date_room='+a+'&seans='+b+'&id_room='+c,function(data){
		switch(data){
			case'1':alert('Ошибка введите имя или телефон!');break;
			case'2':alert('Время уже занто!');break;
			default:$('#rooms_table').html(data);hide_z();
		}
	});
}

function refrash(){
	$.post('/admin_bron.php','refrash=1',function(data){
		switch(data){
			case'1':alert('Ошибка!');break;
			default:$('#rooms_table').html(data);
		}
	});
}

setTimeout('refrash();',200);

function days(a){
	$.post('/admin_bron.php','days_re='+a,function(data){
		switch(data){
			case'1':alert('Ошибка!');break;
			default:$('#rooms_table').html(data);
		}
	});
}
</script>
<?
echo '<div style="width:75%;margin:auto;color:#fff;font-size:15px;">
<p style="margin-bottom:10px;">Для того, что бы забронировать или открыть игру, нажмите на нужное время</p>

';
echo '<table class="bron_room" id="rooms_table" style="margin-bottom: 30px;">';
$date=date("d.m.Y");
echo getRooms_admin($date,$db);
echo '</table>';
echo'</div>';
?>