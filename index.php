<?php
session_start();
if(isset($_SESSION['userid'])){
header("Location: my.php");
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
    <title>签到管理系统</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
				<li class="active"><a href="#">首页</a></li>
				<li><a href="my.php">个人中心</a></li>
				<li><a onclick="alert('Version: v1.15 \n ReleaseDate: 2019/10/30 \n BY: lhytech')">系统信息</a></li>
			</ul>
		</div>
		<!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
      <div class="starter-template">
	  <div class="alert alert-info" role="alert"><strong>欢迎访问签到管理系统</strong><br>进行签到请登录</div>
        <div class="col-sm-4" style="width: 100%; padding-right: 0px; padding-left: 0px;">
        </div>
		<button type="button" class="btn btn-lg btn-success" style="width: 100%;" onClick="window.location.href = 'login.php'"> 扫 脸 登 录 </button>
      </div>
	  <div style="text-align: center;"><a href="https://www.bootcss.com/">Bootstarp</a>&nbsp;&nbsp;Powered by <a href="https://www.lhytech.cn/">LhyTech</a>.</div>
    </div>
	<!-- /.container -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>