<?php
session_start();
if($_GET['action'] == "logout"){
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
	echo "<script language=\"javascript\">";
    echo "alert('注销登录成功！');";
    echo "document.location=\"index.php\"";
    echo "</script>";
	exit();
}
if(isset($_SESSION['userid'])){
header("Location: my.php");
exit();
}
require_once 'AipFace/AipFace.php';
// 你的 APPID AK SK
const APP_ID = 'appid';
const API_KEY = 'ak';
const SECRET_KEY = 'sk';

$client = new AipFace(APP_ID, API_KEY, SECRET_KEY);

if(isset($_POST['pic'])){
$base64 = $_POST['pic'];
$image = substr($base64,22);
$imageType = "BASE64";
$groupIdList = "lhytech";

// 如果有可选参数
$options = array();
$options["max_face_num"] = 1;
$options["match_threshold"] = 80;
$options["quality_control"] = "NORMAL";
$options["liveness_control"] = "NORMAL";
$options["max_user_num"] = 1;

// 带参数调用人脸搜索
$result = $client -> search($image, $imageType, $groupIdList, $options);

function unicode_decode($name)
{
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches))
    {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++)
        {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0)
            {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name .= $c;
            }
            else
            {
                $name .= $str;
            }
        }
    }
    return $name;
}
$out = json_encode($result);
echo '原始返回数据：<br>',$out,'<br>';
$face_token = substr($out,125,32);
$user_id = substr($out,205,3);
$user_info = substr($out,223,18);
$score = substr($out,251,15);
echo '格式化数据：<br>';
$code = substr($out,14,1);
if($code == 0){
	$id1 = strpos($out,'"user_id":"',0);
	$id2 = strpos($out,'","user_info":',0);
	$id3 =  substr($out,$id1+11,$id2-$id1-11);
	$_SESSION['userid'] = $id3;
	$name1 = strpos($out,'"user_info":"',0);
	$name2 = strpos($out,'","score":',0);
	$name3 =  substr($out,$name1+11,$name2-$name1-11);
	$_SESSION['username'] = unicode_decode($name3);
	header("location: my.php");
}
else{
	echo "<script language=\"javascript\">";
    echo "alert('刷脸登录失败，请重试');";
    echo "document.location=\"login.php\"";
    echo "</script>";
}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="favicon.png">
<title>用户登录 - 签到管理系统</title>
<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="css/starter-template.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="#">签到管理系统</a>
	 </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a href="index.php">首页</a></li>
        <li><a href="my.php">个人中心</a></li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</nav>
<div class="container">
  <div class="starter-template">
    <div class="col-sm-4" style="width: 100%; padding-right: 0px; padding-left: 0px;">
      <video id="video" width="270 px" height="480 px" autoplay></video>
      <form name="myForm" method="POST">
        <input type="hidden" id="pic" name="pic"></input>
        <input id="snap" class="btn btn-lg btn-success" style="width: 100%; display: none;" type="submit" name="submit" onclick="takePhoto()" value=" 提 交 " />
        <input id="wait" class="btn btn-lg btn-success" style="width: 100%; display: none;" type="wait" name="wait" value="提交中……" disabled />
      </form>
      <canvas id="canvas" width="270 px" height="480 px" hidden="hidden"></canvas>
      <script>
		var height = window.screen.height;
		var width = window.screen.width;
		var h = 480;
		var w = 270;
		if (width < height){
			var h = 270;
			var w = 480;
		}
    //获得video摄像头区域
    let video = document.getElementById("video");
    function getMedia() {
		var open = document.getElementById("open");
		open.setAttribute("hidden",true);
		open.style.display = "none";
		snap.style.display = "";
        let constraints = {
            video: {width: w, height: h, facingMode: 'user'},
            audio: false
        };
        /*
        这里介绍新的方法:H5新媒体接口 navigator.mediaDevices.getUserMedia()
        这个方法会提示用户是否允许媒体输入(主要包括相机,视频采集设备,屏幕共享服务,麦克风,A/D转换器等),返回的是一个Promise对象。
        如果用户同意使用权限,则会将 MediaStream对象作为resolve()的参数传给then()
        如果用户拒绝使用权限,或者请求的媒体资源不可用,则会将 PermissionDeniedError作为reject()的参数传给catch()
        */
        let promise = navigator.mediaDevices.getUserMedia(constraints);
        promise.then(function (MediaStream) {
            video.srcObject = MediaStream;
            video.play();
        }).catch(function (PermissionDeniedError) {
            console.log(PermissionDeniedError);
        })
    }
    function takePhoto() {
		snap.style.display = "none";
		wait.style.display = "";
        //获得Canvas对象
        let canvas = document.getElementById("canvas");
        let ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, 270, 480);
		const imgUrl = canvas.toDataURL("image/png");
		var myForm = document.forms['myForm'];
		myForm.pic.value = imgUrl;
		myForm.submit();
    }
</script> 
    </div>
    <button id="open" type="button" class="btn btn-lg btn-primary" style="width: 100%;" onclick="getMedia()">点击开启刷脸登录</button>
   </div>
  <div style="text-align: center;"><a href="https://www.bootcss.com/">Bootstarp</a>&nbsp;&nbsp;Powered by <a href="https://www.lhytech.cn/">LhyTech</a>.</div>
</div>
<!-- /.container --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-3.4.1.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>