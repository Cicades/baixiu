<?php
if (empty($_FILES['avatar'])) {
	exit('please upload your avatar.');
}
$avatar=$_FILES['avatar'];
if ($avatar['error']!==UPLOAD_ERR_OK) {
	exit('fail to upload your avatar');
}
//校驗文件類型及大小
//持久化
$ext_name=pathinfo($avatar['name'],PATHINFO_EXTENSION);
$target='../../static/uploads/avatars/'.uniqid().'.'.$ext_name;
if (!file_exists('../../static/uploads/avatars')) {
	mkdir('../../static/uploads/avatars');
}
if (!move_uploaded_file($avatar['tmp_name'], $target)) {
	exit('上傳文件失敗！');
}
echo substr($target, 5);