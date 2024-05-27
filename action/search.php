<?
include "./core.php";

if(isset($_POST['search_button'])){
$search = $_POST['search_name'];
$result = $link->query("SELECT name FROM photo WHERE name LIKE $search");
}
header("Location: ".$_SERVER['HTTP_REFERER']);
?>