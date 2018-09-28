<?php  
include_once '../functions.php';
if (empty($_GET['id'])) {
	exit('删除失败！');
}
$id=$_GET['id'];
$affected_rows=xiu_excute("delete from posts where id in (".$id.")");
$affected_rows>0?header('Location:'.$_SERVER['HTTP_REFERER']):exit('删除失败！');