<?php
/*****************************
*���ݿ�����
*****************************/
$conn = mysqli_connect("localhost","root","password");
if (!$conn){
	die("�������ݿ�ʧ�ܣ�" . mysqli_error());
}
mysqli_select_db($conn,"sign");
//�ַ�ת��������
mysqli_query($conn,"set character set 'utf8'");
//д��
mysqli_query($conn,"set names 'utf8'");
?>