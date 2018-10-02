<?php
include_once '../../functions.php';
if (empty($_GET['id'])) {
	exit('删除失败！');
}
$id=$_GET['id'];
header('Content-Type:application/json');
$res=xiu_excute("delete from comments where id in ({$id})");
echo $res>0?'true':'false';