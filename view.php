<?php
session_start();
if(!isset($_SESSION['userid'])){
header("Location: index.php");
exit();
}
include('conn.php');
if(isset($_GET['uid'])){
$uid = $_GET['uid'];
$date = $_GET['date'];
$log_query = mysqli_query($conn,"select id,uid,date,filename from logs where uid='$uid' and date='$date'");
$log = mysqli_fetch_array($log_query);
$tid = $log['uid'];
$user_query = mysqli_query($conn,"select username from users where uid='$tid' limit 1");
$user = mysqli_fetch_array($user_query);
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
<title>查看图片 - 签到管理系统</title>
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
        <li><a href="login.php">个人中心</a></li>
      </ul>
    </div> <!--/.nav-collapse --> 
  </div>
</nav>
<div class="container">
	<div class="starter-template">
		<div class="col-sm-4" style="width: 100%; padding-right: 0px; padding-left: 0px;">
			<?php
				if($log){
					echo '<div class="panel panel-success"><div class="panel-heading"><h3 class="panel-title">详细信息</h3></div>';
					echo '<div class="panel-body">姓名：',$user['username'],'<br>','日期：',$log['date'],'</div></div>';
					echo '<p>（点击缩略图可以查看大图）</p>';
					$log_query = mysqli_query($conn,"select id,uid,date,filename from logs where uid='$uid' and date='$date' order by id desc");
					while($log = mysqli_fetch_array($log_query)){
						echo '<a href="https://totp.lhytech.cn/sign/images/',$log['filename'],'"','><img src="',"images/thumbs/",$log['filename'],'"','width="144 px"'," />&nbsp;</a>";
					}
				}
				else{
					echo '<div class="alert alert-danger" role="alert"><strong>错误！</strong><br>该用户今日尚未进行签到。</div>';
				}
			?>
			<p></p>
			<button type="button" class="btn btn-lg btn-primary" style="width: 100%;" onClick="window.history.go(-1);">返回上页</button>
	</div>
</div>
<div style="text-align: center;"><a href="https://www.bootcss.com/">Bootstarp</a>&nbsp;&nbsp;Powered by <a href="https://www.lhytech.cn">LhyTech</a>.</div>
</div> <!-- /.container --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-3.4.1.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>