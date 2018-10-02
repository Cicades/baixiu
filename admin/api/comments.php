<?php
require_once '../../functions.php';
header('Content-Type:application/json');
$total_records=xiu_fetch_single('select count(1) as num from comments c inner join posts p on c.parent_id=p.id')['num'];
$page_size=20;
$total_pages=ceil($total_records/$page_size);
$page=empty($_GET['page'])?1:($_GET['page']>$total_pages?$total_pages:$_GET['page']);
$page_offset=($page-1)*$page_size;
$sql=sprintf("select c.*,p.title as parent_title from comments c inner join posts p on c.parent_id=p.id ORDER BY c.created DESC limit %d,%d;",$page_offset,$page_size);
$comments=xiu_fetch($sql);
$data=['total_pages'=>$total_pages,'comments'=>$comments];
echo json_encode($data);