<?php
include("includeSettings.php");			//����� ��� ������� ��� ���� ������� ������� ��������
$mobile = ""; 							//��� �������� �� �������
$password = "";							//��������  �� �������

$resultType = 0;						//���� ����� ��� ������� ������� �� �������
										//0: ����� ������� ��� �� �� �������
										//1: ����� ���� ������� ������� �� �������	

echo checkAlphasSender($mobile,$password,$resultType);
?>