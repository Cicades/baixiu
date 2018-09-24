<?php
require_once '../../config.php';
function get_avatar(){
	if (empty($_POST['email'])) {
		return;
	}
	$conn=mysqli_connect(xiu_DB_HOST,xiu_DB_USER_NAME,xiu_DB_PASSWORD,xiu_DB_NAME);
	if (!$conn) {
		return;
	}
	$email=$_POST['email'];
	$query=mysqli_query($conn,"SELECT * FROM users WHERE `email`='{$email}' limit 1;");
	$avatar=mysqli_fetch_assoc($query)['avatar'];
	echo $avatar;
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
	get_avatar();
}