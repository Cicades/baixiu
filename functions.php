<?php
include_once 'config.php';
session_start();
/**
 * 判断用户是否已经登录
 * @return [type] [description]
 */
function xiu_get_current_user(){
	if (empty($_SESSION['user_logined'])) {
		header('Location:/admin/login.php');
	}
	return $_SESSION['user_logined'];
}
/**
 * 数据库查询操作
 * @return [type] [description]
 */
function xiu_query($sql){
	$con=mysqli_connect(xiu_DB_HOST,xiu_DB_USER_NAME,xiu_DB_PASSWORD,xiu_DB_NAME);
	if (!$con) {
		exit('数据库连接失败1');
	}
	$query=mysqli_query($con,$sql);
	if (!$query) {
		return false;
	}
	return $query;
}
/**
 * 数据库查询
 * @param  [string] $sql [description]
 * @return [type]      [description]
 */
function xiu_fetch($sql)
{
	$query=xiu_query($sql);
	while ($row=mysqli_fetch_assoc($query)) {
		$data[]=$row;
	}
	mysqli_free_result($query);
	return $data;
}
/**
 * 获取单条数据
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
function xiu_fetch_single($sql){
	$res=xiu_fetch($sql);
	return isset($res)?$res[0]:false;
}

function xiu_excute($sql){
	$con=mysqli_connect(xiu_DB_HOST,xiu_DB_USER_NAME,xiu_DB_PASSWORD,xiu_DB_NAME);
	if (!$con) {
		exit('数据库连接失败!');
	}
	$query=mysqli_query($con,$sql);
	if (!$query) {
		return false;
	}
	$affected_rows=mysqli_affected_rows($con);
	return $affected_rows;
}