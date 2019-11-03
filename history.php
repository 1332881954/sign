<?php
header("Cache-control: private");
session_start();
if(!isset($_SESSION['userid'])){
header("Location: index.php");
exit();
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
    <title>历史记录 - 签到管理系统</title>
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
			<li><a href="my.php">个人中心</a></li>
			</ul>
		</div>
		<!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
      <div class="starter-template">
        <div class="col-sm-4" style="width: 100%; padding-right: 0px; padding-left: 0px;">
          <div class="alert alert-info" role="alert"><strong>请选择您想要查询的日期</strong></div>
		  <form id="RegForm" class="form-signin" method="post">
				<input type="date" id="date" name="date" class="form-control" placeholder="请输入测试结束时间" required><br>
				<input class="btn btn-lg btn-success" style="width: 100%;" id="submit" name="submit" type="submit" value="&emsp;提&emsp;交&emsp;" /><p></p>
		</form>
<?php
if(isset($_POST['date'])){
	echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title">',$_POST['date'],' 签到情况统计</h3></div><div class="panel-body">';
	include('conn.php');
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
		$date = $_POST['date'];
		$query="SELECT uid,date FROM `logs` WHERE uid='$tid' AND date='$date' ";
		$date_query = mysqli_query($conn,$query);
		$date_row = mysqli_fetch_array($date_query);
		if($date_row){
			$result = '<font color="green">已签到</font>';
		}
		else{
			$result = '<font color="red">未签到</font>';
		}
		echo '<td>',$result,'</td>';
		echo '<td>','<button type="button" class="btn btn-primary" onclick="window.location=',"'view.php?uid=",$date_row['uid'],'&date=',$date_row['date'],"'",'">查看图片</button>',"</td></tr>\n";
	}
	echo '</tbody></table></div></div>';
}
?>
		 <button type="button" class="btn btn-lg btn-primary" style="width: 100%;" onClick="window.location.href='my.php'">返回上页</button><p></p>
        </div>
       </div>
	  <div style="text-align: center;"><a href="https://www.bootcss.com/">Bootstarp</a>&nbsp;&nbsp;Powered by <a href="https://www.lhytech.cn">LhyTech</a>.</div>
    </div> <!-- /.container -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>