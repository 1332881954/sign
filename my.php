<?php
session_start();
if(!isset($_SESSION['userid'])){
header("Location: index.php");
exit();
}
$date = date('Y-m-d');
$userid = $_SESSION['userid'];
include('conn.php');
$user_query = mysqli_query($conn,"SELECT username,room FROM `users` WHERE uid='$userid' limit 1");
$user_row = mysqli_fetch_array($user_query);
$query = "SELECT id FROM `logs` WHERE uid='$userid' AND date='$date' limit 1";
$stat_query = mysqli_query($conn,$query);
$stat_row = mysqli_fetch_array($stat_query);
if($stat_row){
	$stat = '<font color="green">已签到</font>';
}
else{
	$stat = '<font color="red">未签到</font>';
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
	<title>个人中心 - 签到管理系统</title>
	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- Custom styles for this template -->
	<link href="css/starter-template.css" rel="stylesheet">
	<style>
		th {
			text-align: center;
		}
	</style>
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
					<li class="active"><a href="#">个人中心</a></li>
					<li><a onclick="alert('Version: v1.15 \n ReleaseDate: 2019/10/30 \n BY: lhytech')">系统信息</a></li>
					<li><a href="login.php?action=logout">退出登录</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<!--/.nav-collapse -->
	<div class="container">
		<div class="starter-template">
			<div class="col-sm-4" style="width: 100%; padding-right: 0px; padding-left: 0px;">
				<div class="panel panel-success">
					<div class="panel-heading"><h3 class="panel-title">用户信息</h3></div>
					<div class="panel-body"><?php echo '姓名：',$user_row['username'],'&emsp;宿舍号：',$user_row['room'],'<br>日期：',$date,'&emsp;状态：',$stat; ?></div>
				</div>
				<button type="button" class="btn btn-lg btn-success" style="width: 100%;" onclick="window.location.href='sign.php'">每日签到</button><p></p>
				<div class="panel panel-info">
					<div class="panel-heading"><h3 class="panel-title">今日签到情况统计</h3></div>
					<div class="panel-body">
<?php
	$sql="SELECT uid,username FROM `users`";
	$user_query = mysqli_query($conn,$sql);
	echo '<table class="table table-bordered" style="text-align: center;">';
	echo '<thead>';
	echo '<tr><th>姓名</th><th>签到状态</th><th>查看详情</th></tr>';
	echo '</thead>';
	echo '<tbody>';
	while($user_row=mysqli_fetch_array($user_query)){
		echo '<tr><td>',$user_row['username'],'</td>';
		$tid = $user_row['uid'];
		$query="SELECT id FROM `logs` WHERE uid='$tid' AND date='$date' limit 1";
		$date_query = mysqli_query($conn,$query);
		$date_row = mysqli_fetch_array($date_query);
		if($date_row){
			$result = '<font color="green">已签到</font>';
		}
		else{
			$result = '<font color="red">未签到</font>';
		}
		echo '<td>',$result,'</td>';
		echo '<td>','<button type="button" class="btn btn-primary" onclick="window.location=',"'view.php?uid=",$user_row['uid'],'&date=',$date,"'",'">查看图片</button>',"</td></tr>\n";
	}
	echo '</tbody>';
	echo '</table>';
?>
					</div>
				</div>
			</div>
			<button type="button" class="btn btn-lg btn-info" style="width: 100%;" onclick="window.location.href='history.php'">查看历史签到记录</button><p></p>
			<button type="button" class="btn btn-lg btn-danger" style="width: 100%;" onclick="window.location.href='login.php?action=logout'">退出登录</button>
		</div>
		<div style="text-align: center;"><a href="https://www.bootcss.com/">Bootstarp</a>&nbsp;&nbsp;Powered by <a href="https://www.lhytech.cn/">LhyTech</a>.</div>
	</div>
	<!-- /.container -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>