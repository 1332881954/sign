<?php
session_start();
if(!isset($_SESSION['userid'])){
header("Location: index.php");
exit();
}
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
include('conn.php');
$date = date('Y-m-d');
if(isset($_POST['img'])){
	define('UPLOAD_DIR','images/');
	$img = $_POST['img'];
	$img = str_replace('data:image/webp;base64,','',$img);
	$img = str_replace(' ','+',$img);
	$data = base64_decode($img);
	$filename = date('YmdHis') . '_' . $userid . '.webp';
	$file = UPLOAD_DIR . $filename;
	$success = file_put_contents($file,$data);
	$img_info = getimagesize($file);
	$image_p = imagecreatetruecolor($img_info['0'] / 8, $img_info['1'] / 8);
	$image = imagecreatefromwebp($file);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0,$img_info['0'] / 8, $img_info['1'] / 8, $img_info['0'], $img_info['1']);
	imagewebp($image_p,'images/thumbs/' . $filename);
	$sql = "INSERT INTO `logs` (uid,date,filename) VALUES ('$userid','$date','$filename')";
	if(mysqli_query($conn,$sql)){
		echo "<script language=\"javascript\">";
		echo "alert('每日签到成功！');";
		echo "document.location=\"my.php\"";
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
	<title>每日签到 - 签到管理系统</title>
	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css?v=1" rel="stylesheet">
	<!-- Custom styles for this template -->
	<link href="css/starter-template.css" rel="stylesheet">
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">签到管理系统</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="index.php">首页</a></li>
					<li><a href="login.php">个人中心</a></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</nav>
	<div class="container">
		<div class="starter-template">
			<div class="col-sm-4" style="width: 100%; padding-right: 0px; padding-left: 0px;">
				<div id="imgbox1">
				</div>
				<form name="myForm" method="POST">
					<input type="hidden" id="img" name="img" />
					<br>
					<input type="file" id="pic" name="pic" onchange="changefile(files)" accept="image/*" class="btn btn-lg btn-primary" style="width: 100%; display: block;" />
					<input type="file" id="upload" name="upload" onchange="changefile(files)" accept="image/*" class="btn btn-lg btn-primary" style="width: 100%; display: none;" disabled />
					<br>
					<input id="post" class="btn btn-lg btn-success" style="width: 100%; display: none;" type="button" name="post" onclick="compress()" value=" 提 交 " />
					<input id="wait" class="btn btn-lg btn-success" style="width: 100%; display: none;" type="wait" name="wait" value="提交中……" disabled />
				</form>
<script>
	function changefile(files){
		post.style.display = "";
		let url_arry=[];
		let file_arry=[];
		for (var i=0;i<files.length;i++){
			let file = files[i];
			file_arry.push(file);
			let reader = new FileReader();
			reader.readAsDataURL(file);
			let that = this;
			reader.onload = function(e) {
			let url = e.target.result;
			url_arry.push('<img src="'+url+'" width="100%" />');
			document.getElementById('imgbox1').innerHTML=url_arry.join('');
			};
		}
    }
	
	function compress() {
		post.style.display = "none";
		pic.style.display = "none";
		upload.style.display = "";
		wait.style.display = "";
		let fileObj = document.getElementById('pic').files[0]; //上传文件的对象
		let reader = new FileReader();
		reader.readAsDataURL(fileObj);
		reader.onload = function (e) {
			let image = new Image(); //新建一个img标签(还没嵌入DOM节点)
			image.src = e.target.result;
			image.onload = function() {
				let canvas = document.createElement('canvas');
				context = canvas.getContext('2d');
				canvas.width = image.width;
				canvas.height = image.height;
				context.drawImage(image, 0, 0, image.width, image.height);
				var data = canvas.toDataURL('image/webp');
				var myForm = document.forms['myForm'];
				myForm.img.value = data;
				myForm.submit();
			}
		}
	}
</script>
			</div>
		</div>
		<div style="text-align: center;"><a href="https://www.bootcss.com/">Bootstarp</a>&nbsp;&nbsp;Powered by <a href="https://www.lhytech.cn">LhyTech</a>.</div>
	</div>
	<!-- /.container -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>