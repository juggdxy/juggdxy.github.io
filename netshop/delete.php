<?php 
header("Content-type: text/html; charset=utf-8");
include_once './lib/fun.php';
if(!checkLogin()){
	msg(2, '请登录', 'login.php');
}

//校验URL中商品ID
$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';
if(!$goodsId){
    msg(2,'参数非法','index.php');
}

//根据商品ID查询商品信息
$con = mysqlInit('localhost','root','root','netshop');
$sql = "SELECT `id` FROM `im_goods` WHERE `id` = {$goodsId}";
$obj = mysql_query($sql);


if (!$goods = mysql_fetch_assoc($obj)) {
    msg(2,'商品不存在','index.php');
}

//商品删除操作
$sql = "DELETE FROM `im_goods` where `id` = {$goodsId} LIMIT 1";

if($result = mysql_query($sql)){
	msg(1,'删除成功','index.php');
}else{
	msg(2,'删除失败','index.php');
}


 ?>