<?php

header("Content-type: text/html; charset=utf-8");
include_once './lib/fun.php';
if(!checkLogin()){
	msg(2, '请登录', 'login.php');
}

if(!empty($_POST['name'])){
	$con = mysqlInit('localhost','root','root','netshop');

	$goodsId = intval($_POST['id']);
	if(!$goodsId){
		msg(2,'参数非法');
	}
	//根据商品ID校验商品信息
	$sql = "SELECT * FROM `im_goods` WHERE `id` = {$goodsId}";
	$obj = mysql_query($sql);
	$goods = mysql_fetch_assoc($obj);
	if(!$goods){
		msg(2,'商品NO存在','index.php');
	}


	$name = mysql_real_escape_string(trim($_POST['name']));

	$price = intval($_POST['price']);

	$des = mysql_real_escape_string(trim($_POST['des']));

	$content = mysql_real_escape_string(trim($_POST['content']));

	// $userId = $user['id'];

	// $now = $_SERVER['REQUEST_TIME'];

	// $pic = imgUpload($_FILES['file']);

	//各种输入验证
	$nameLength = mb_strlen($name,'utf8');
	if($nameLength <= 0 || $nameLength > 30){
		msg(2,'名称应在1-30字符之间');
	}

	if($price <= 0 || $price > 999999999){
		msg(2,'价格小于999999999');
	}

	$desLength = mb_strlen($des,'utf8');
	if($desLength <= 0 || $desLength > 100){
		msg(2,'简介应在1-100字符之内');
	}

	if(empty($content)){
		msg(2,'详情不能为空');
	}
		
	//更新数组
	$update = array(
		'name'=>$name,
		'price'=>$price,
		'des'=>$des,
		'content'=>$content
	);

	//校验商品图片
	if($_FILES['file']['size'] > 0){
		$pic = imgUpload($_FILES['file']);
		$update['pic'] = $pic;
	}


	//只更新被更改的信息
	foreach($update as $k=>$v){
		if($goods[$k] == $v){
			unset($update[$k]);
		}
	}

	if(empty($update))
    {
        msg(1, '操作OO成功', 'edit.php?id=' . $goodsId);//edit.php的第十七行不能传localhost
    }

	

	//更新sql
	$updateSql = '';
	foreach($update as $k=>$v){
		$updateSql .= "`{$k}`='{$v}',";
	}
	//去除多余逗号
	$updateSql = rtrim($updateSql,',');

	unset($sql,$obj,$result);
	$sql = "UPDATE `im_goods` SET{$updateSql} WHERE `id`={$goodsId}";
	
	$result = mysql_query($sql);
	if($result){
		msg(1,'操作成功','index.php');
	}else{
		msg(2,'操作成功','www.baidu.com');
	}

}else{
	msg(2,'路由非法','index.php');
}





