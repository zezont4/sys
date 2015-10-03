<?php
//������� ������ ��������  ��� ����� �����	
include("function/checkSendPorts.php");
if(fsockopenTest() == 5)
{
	//������ ���� fsockopen
	include("function/fsockopen.php");
}
elseif(curlTest() == 5)
{
	//������ ���� curl
	include("function/curl.php");
}
elseif(fopenTest() == 3)
{
	//������ ���� fopen
	include("function/fopen.php");
}
elseif(fileTest() == 3)
{
	//������ ���� curl
	include("function/file.php");
}
elseif(filegetcontentsTest() == 3)
{
	//������ ���� fopen
	include("function/filegetcontents.php");
}
else
{
	//������ �� ����� ������� �� ��� �� ��� ����� �� ���� �����
	echo "�� ���� ���� ���� �����<br>���� ��������� ���� ���� �������� ������ fsockopen � curlSMS � fopenSMS";
}
//������ ������ ������� �� ����� ������� ��� ��� ��
include("function/functionPrintResult.php");

//���� ����� �� ������� ��� ������� UNICODE
include("function/functionUnicode.php");

//������ ��� ������ �� ��� ���� ����� ������� ��� �����
$undefinedResult = "����� ������� ��� ����� ������ ������� �����";

//������� ������� �� ���� ��� ����� �������
$arraySendStatus = array();
$arraySendStatus[0] = "����� ������� ����� ����";
$arraySendStatus[1] = "����� ������� ����";

//������� ������� �� ���� ����� ���� ������ 
$arrayChangePassword = array();
$arrayChangePassword[0] = "�� ��� ������� �������";
$arrayChangePassword[1] = "��� ������ �������� ��� ����";
$arrayChangePassword[2] = "���� ������ ������ ������� ��� �����";
$arrayChangePassword[3] = "��� ����� ����� ���� ������ �����";

//������� ������� �� ���� ������� ���� ������
$arrayForgetPassword = array();
$arrayForgetPassword[0] = "�� ��� ������� �������";
$arrayForgetPassword[1] = "��� ������ �������� ��� ����";
$arrayForgetPassword[2] = "������� ����� ������� ��� �����";
$arrayForgetPassword[3] = "�� ����� ���� ������ ��� ������ �����";
$arrayForgetPassword[4] = "����� ��� ���� ������ ����� �������";
$arrayForgetPassword[5] = "�� ����� ���� ������ ��� ������� �����";
$arrayForgetPassword[6] = "������� ����� ������� ��� ����";
$arrayForgetPassword[7] = "��� ������ �������� ��� ����";

//������� ������� �� ���� �������
$arraySendMsg = array();
$arraySendMsg[0] = "�� ��� ������� �������";
$arraySendMsg[1] = "��� ����� ������� �����";
$arraySendMsg[2] = "����� 0,������ ����� ������� ��� ����� �� ����� �������";
$arraySendMsg[3] = "����� ��� ���� ������ ����� �������";
$arraySendMsg[4] = "��� ������ �������� ��� ����";
$arraySendMsg[5] = "���� ������ ������ ������� ��� �����";
$arraySendMsg[6] = "���� �������� ��� �����,���� ������� �� ����";
$arraySendMsg[7] = "���� ������� ��� ����";
$arraySendMsg[8] = "����� ��� ������� ���� ��������";
$arraySendMsg[9] = "������ ������ ���������";
$arraySendMsg[10] = "��� ������� �� ����� ��� �������";
$arraySendMsg[11] = "������� �� ���� �� ����� ����� ���� �������. ��� ���� ����� �������� ���� �������";
$arraySendMsg[12] = "����� ������� ��� ����";
$arraySendMsg[13] = "����� ������ �� ��� ���� �� �� ���� ����� BS �� ����� �������";
$arraySendMsg[14] = "��� ���� �� �������� �������� ��� ������";
$arraySendMsg[15] = "������� ������ ��� ��� ������ �� ��� �����";
$arraySendMsg[16] = "��� ������ ���ۡ �� ��� ����";
$arraySendMsg[17] = "�� ������� ��� ����� �� ��� ���� ���� ����";

$arrayDeleteSMS = array();
$arrayDeleteSMS[1] = "��� ����� ����� �����";
$arrayDeleteSMS[2] = "��� ������ ��� ����";
$arrayDeleteSMS[3] = "���� ������ ��� �����";
$arrayDeleteSMS[4] = "��������� ������� ����� ��� ������ �� ��� deleteKey ����";

//������� ������� �� ���� ��� ������
$arrayBalance = array();
$arrayBalance[0] = "�� ��� ������� �������";
$arrayBalance[1] = "��� ������ ��� ����";
$arrayBalance[2] = "���� ������ ��� �����";
$arrayBalance[3] = "����� ������ �� %s ���� �� ��� %s ����";

//������� ������� �� ���� ������ �� ��� ��� ������ - ������ ��������
$arrayCheckAlphasSender = array();
$arrayCheckAlphasSender[0] = "�� ��� ������� �������";
$arrayCheckAlphasSender[1] = "��� ������ ��� ����";
$arrayCheckAlphasSender[2] = "���� ������ ��� �����";

//������� ������� �� ���� ��� ��� ������ - ������ ��������
$arrayAddAlphaSender = array();
$arrayAddAlphaSender[0] = "�� ��� ������� �������";
$arrayAddAlphaSender[1] = "��� ������ ��� ����";
$arrayAddAlphaSender[2] = "���� ������ ��� �����";
$arrayAddAlphaSender[3] = "��� ��� ������ ������� ���� �� 11 ����";
$arrayAddAlphaSender[4] = "�� ����� ����� �����";

//������� ������� �� ���� ��� ��� ������ - ��� ������
$arrayAddSender = array();
$arrayAddSender[0] = "�� ��� ������� �������";
$arrayAddSender[1] = "��� ������ ��� ����";
$arrayAddSender[2] = "���� ������ ��� �����";
$arrayAddSender[3] = "��� ������ '����� ������' ��� ����";
$arrayAddSender[4] = "��� ������ �� ����� ��� ����� ! ";
$arrayAddSender[5] = "����� ��� ���� ������ ��� �������";
$arrayAddSender[6] = "���� ������ ��� �����";

//������� ������� �� ���� ������ �� ��� ����� ��� ������ - ��� ����
$arrayCheckSender = array();
$arrayCheckSender[0] = "��� ������ ��� ����";
$arrayCheckSender[1] = "��� ������ ����";
$arrayCheckSender[2] = "��� ������ �����";
$arrayCheckSender[3] = "��� ������ ��� ����";
$arrayCheckSender[4] = "���� ������ ��� �����";

//������� ������� �� ���� ����� ��� ��� ������ - ��� ����
$arrayActiveSender = array();
$arrayActiveSender[0] = "�� ��� ������� �������";
$arrayActiveSender[1] = "��� ������ ��� ����";
$arrayActiveSender[2] = "���� ������ ��� �����";
$arrayActiveSender[3] = "�� ����� ��� ������";
$arrayActiveSender[4] = "��� ������� ��� ����";
$arrayActiveSender[5] = "senderId ����";

//������� ������� �� ���� ���� �������
$arraySendMsgWK = array();
$arraySendMsgWK[0] = "�� ��� ������� �������";
$arraySendMsgWK[1] = "��� ����� ������� �����";
$arraySendMsgWK[2] = "����� 0,������ ����� ������� ��� ����� �� ����� �������";
$arraySendMsgWK[3] = "����� ��� ���� ������ ����� �������";
$arraySendMsgWK[4] = "��� ������ �������� ��� ����";
$arraySendMsgWK[5] = "���� ������ ������ ������� ��� �����";
$arraySendMsgWK[6] = "���� �������� ��� �����,���� ������� �� ����";
$arraySendMsgWK[7] = "���� ������� ��� ����";
$arraySendMsgWK[8] = "����� ��� ������� ���� ��������";
$arraySendMsgWK[9] = "������ ������ ���������";
$arraySendMsgWK[10] = "��� ������� �� ����� ��� �������";
$arraySendMsgWK[11] = "������� �� ���� �� ����� ����� ���� �������. ��� ���� ����� �������� ���� �������";
$arraySendMsgWK[12] = "����� ������� ��� ����";
$arraySendMsgWK[13] = "����� ������ �� ��� ���� �� �� ���� ����� BS �� ����� �������";
$arraySendMsgWK[14] = "��� ���� �� �������� �������� ��� ������";
$arraySendMsgWK[15] = "������� ������ ��� ��� ������ �� ��� �����";
$arraySendMsgWK[16] = "��� ������ ���ۡ �� ��� ����";
$arraySendMsgWK[17] = "�� ������� ��� ����� �� ��� ���� ���� ����";
?>