<?php 
include_once './lib/fun.php';
if($login = checkLogin()){
	$user = $_SESSION['user'];
}

//校验URL中商品ID
$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';
if(!$goodsId){
    msg(2,'参数非法','index.php');
}

//根据商品ID查询商品信息
$con = mysqlInit('localhost','root','root','netshop');//这里传localhost会使do_edit的82行出错,因为host文件被修改
$sql = "SELECT * FROM `im_goods` WHERE `id` = {$goodsId}";
$obj = mysql_query($sql);


if (!$goods = mysql_fetch_assoc($obj)) {
    msg(2,'商品不存在','index.php');
}

//根据用户ID查询发布人
unset($sql,$obj);
$sql = "SELECT * FROM `im_user` where `id`='{$goods['user_id']}'";
$obj = mysql_query($sql);
$user = mysql_fetch_assoc($obj);

//更新浏览次数
unset($sql,$obj);
$sql = "UPDATE `im_goods` set `view`=`view`+1 where `id`={$goods['id']}";
$obj = mysql_query($sql);




 ?>


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|<?php echo $goods['name']?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css" />
    <link rel="stylesheet" type="text/css" href="./static/css/detail.css" />
</head>
<body class="bgf8">
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul><?php if($login): ?>
        	<li><span>管理员：<?php echo $user['username'] ?></span></li>
            <li><a href="publish.php">发布</a></li>
            <li><a href="login_out.php">退出</a></li>
        	<?php else: ?>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        	<?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="section" style="margin-top:20px;">
        <div class="width1200">
            <div class="fl"><img src="<?php echo $goods['pic']?>" width="720px" height="432px"/></div>
            <div class="fl sec_intru_bg">
                <dl>
                    <dt><?php echo $goods['name']?></dt>
                    <dd>
                        <p>发布人：<span><?php echo $user['username']?></span></p>
                        <p>发布时间：<span><?php echo date('Y年m月d日',$goods['create_time'])?></span></p>
                        <p>修改时间：<span><?php echo date('Y年m月d日',$goods['update_time'])?></span></p>
                        <p>浏览次数：<span><?php echo $goods['view']?></span></p>
                    </dd>
                </dl>
                <ul>
                    <li>售价：<br/><span class="price"><?php echo $goods['price'] ?></span>元</li>
                    <li class="btn"><a href="javascript:;" class="btn btn-bg-red" style="margin-left:38px;">立即购买</a></li>
                    <li class="btn"><a href="javascript:;" class="btn btn-sm-white" style="margin-left:8px;">收藏</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="secion_words">
        <div class="width1200">
            <div class="secion_wordsCon">
                <?php echo $goods['content']?>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</div>
</body>
</html>