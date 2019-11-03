<?php
/*****************************
*数据库连接
*****************************/
$conn = mysqli_connect("localhost","root","password");
if (!$conn){
	die("连接数据库失败：" . mysqli_error());
}
mysqli_select_db($conn,"sign");
//字符转换，读库
mysqli_query($conn,"set character set 'utf8'");
//写库
mysqli_query($conn,"set names 'utf8'");
?>