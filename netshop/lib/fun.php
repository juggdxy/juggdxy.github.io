<?php 
//登陆链接数据库
function mysqlInit($host,$username,$password,$dbName){

	$con = mysql_connect($host,$username,$password);
	if(!$con){
		return false;
	}
	mysql_select_db($dbName);

	mysql_set_charset('utf8');
	
	return $con;
}

//加密函数
function createPassword($password){
	if(!$password){
		return false;
	}

	return md5(md5($password).'DBG');
}

//跳转提示页
function msg($type,$msg=null,$url=null){

	$toUrl = "Location:msg.php?type={$type}";

	$toUrl.=$msg?"&msg={$msg}":'';

	$toUrl.=$url?"&url={$url}":'';

	header($toUrl);
	exit;
}


//文件上传函数
function imgUpload($file){

	//检查上传文件合法性
	if(!is_uploaded_file($file['tmp_name'])){
		msg(2,'请上传合法图像');
	}
    //检查上传文件格式规范性
    $type = $file['type'];
	if(!in_array($type,array("image/png","image/gif","image/jpeg"))){
		msg(2,'请上传正确格式的图像');
	}

	//上传目录
	$uploadPath = './static/file/';
	//上传目录以URL形式访问
	$uploadUrl = '/static/file/';
	//上传文件夹
	$fileDir = date('Y/md/',$_SERVER['REQUEST_TIME']);

	//检查上传目录是否存在，不存在则创建
	if(!is_dir($uploadPath.$fileDir)){
		mkdir($uploadPath.$fileDir,0755,true);
	}

	$ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

	//上传图像名称
	$img = uniqid().mt_rand(1000,9999).'.'.$ext;

	//上传图像地址
	$imgPath = $uploadPath.$fileDir.$img;
	$imgUrl = 'http://localhost/netshop' . $uploadUrl . $fileDir . $img;

	//将上传文件移动到服务器指定文件夹
	if(!move_uploaded_file($file['tmp_name'],$imgPath)){
		msg(2,'服务器繁忙，请稍后再试');
	}
	return $imgUrl;
}

function checkLogin(){
	session_start();
	if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
		return false;
	}
	return true;
}

//获取URL
function getUrl(){
	$url = '';
	$url .= $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
	$url .= $_SERVER['HTTP_HOST'];
	$url .= $_SERVER['REQUEST_URI'];
	return $url;
}

function pageUrl($page, $url = ''){
	$url = empty($url) ? getUrl() : $url;
	//查询URL中是否存在？
	$pos = strpos($url,'?');
	if($pos === false){
		$url .= '?page='.$page;
	}else{
		$queryString = substr($url,$pos+1);
		//将queryString解析为queryArr数组
		parse_str($queryString,$queryArr);
		//如果已存在page，则注销重新赋值
		if(isset($queryArr['page'])){
			unset($queryArr['page']);
		}
		$queryArr['page'] = $page;
		//将queryArr重新拼接成queryString
		$queryStr = http_build_query($queryArr);
		$url = substr($url,0,$pos).'?'.$queryStr;
	}
	return $url;

}



//分页函数
function pages($total, $currentPage, $pageSize, $show = 6){
	$pageStr = '';
	if($total > $pageSize){
		//总页数
		$totalPage = ceil($total/$pageSize);
		//对当前页容错处理
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		//分页起始页
		$from = max(1,($currentPage - intval($show/2)));//show必须为偶数？？？
		//分页结束页
		$to = $from+$show-1;
		//当结束页大于总页数
		if($to > $totalPage){
			$to = $totalPage;
			$from = max(1,$to - $show + 1);
		}

		$pageStr .= '<div class="page-nav">';
		$pageStr .= '<ul>';
		//当前页大于1时，显示首页和上一页
		if($currentPage > 1){
			$pageStr .= "<li><a href='".pageUrl(1)."'>首页</a></li>";
			$pageStr .= "<li><a href='".pageUrl($currentPage-1)."'>上一页</a></li>";
		}

		if($from > 1){
			$pageStr .='<li>...</li>';			
		}

		for($i=$from;$i<=$to;$i++){
			if($i != $currentPage){
				$pageStr .= "<li><a href='" . pageUrl($i) . "'>{$i}</a></li>";
			}else{
				$pageStr .= "<li><span class='curr-page'>{$i}</span></li>";
			}
		}

		if($to < $totalPage){
			$pageStr .='<li>...</li>';			
		}


		if($currentPage < $totalPage){
			$pageStr .= "<li><a href='".pageUrl($currentPage+1)."'>下一页</a></li>";
			$pageStr .= "<li><a href='".pageUrl($totalPage)."'>尾页</a></li>";
		}

		$pageStr .= '</ul>';
		$pageStr .= '</div>';
	}
	return $pageStr;
}



 ?>

