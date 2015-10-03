<?php
//с┼╬╩э╟╤ ╟с╧╟с╔ ╟суф╟╙╚╔  ═╙╚ ╬╒╟╞╒ уц▐┌▀	
include("function/checkSendPorts.php");
if(fsockopenTest() == 5)
{
	//┼╬╩╚╟╤ ╧╟с╔ fsockopen
	include("function/fsockopen.php");
}
elseif(curlTest() == 5)
{
	//┼╬╩╚╟╤ ╧╟с╔ curl
	include("function/curl.php");
}
elseif(fopenTest() == 3)
{
	//┼╬╩╚╟╤ ╧╟с╔ fopen
	include("function/fopen.php");
}
elseif(fileTest() == 3)
{
	//┼╬╩╚╟╤ ╧╟с╔ curl
	include("function/file.php");
}
elseif(filegetcontentsTest() == 3)
{
	//┼╬╩╚╟╤ ╧╟с╔ fopen
	include("function/filegetcontents.php");
}
else
{
	//сс╬╤ц╠ уф ┌усэ╔ ╟с┼╤╙╟с ▌э ═╟с су э▀ф хф╟с▀ ├э ╧╟с╔ у▌┌сх
	echo "с╟ эц╠╧ с╧э▀ ╧ц╟с у▌┌с╔<br>╤╟╠┌ ╟с┼╙╩╓╟▌╔ ц▌┌с ┼═╧ь ╟с╚ц╟╚╟╩ ╟с╦с╟╦ fsockopen ц curlSMS ц fopenSMS";
}
//с╪╚╟┌╔ ╟с▐эу╔ ╟сф╟╩╠х уф ╚ц╟╚╔ ╟с┼╤╙╟с ┌сь ╘▀с ф╒
include("function/functionPrintResult.php");

//╧╟с╔ ╩╘▌э╤ ф╒ ╟с╤╙╟сх ┼сь ╟с╩╤уэ╥ UNICODE
include("function/functionUnicode.php");

//╩╙╩╬╧у х╨х ╟с▐эу╔ ▌э ═╟с ▀╟ф╩ ф╩э╠╔ ╟с┌усэх █э╤ у┌╤▌х
$undefinedResult = "ф╩э╠╔ ╟с┌усэ╔ █э╤ у┌╤▌хб ╟с╤╠╟┴ ╟су═╟цс у╠╧╧╟";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ▌═╒ ┼╤╙╟с уц╚╟эсэ
$arraySendStatus = array();
$arraySendStatus[0] = "ф┌╩╨╤ ╟с┼╤╙╟с у╩ц▐▌ ╟с┬ф";
$arraySendStatus[1] = "эу▀ф▀ ╟с┼╤╙╟с ╟с┬ф";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╩█ээ╤ ▀су╔ ╟су╤ц╤ 
$arrayChangePassword = array();
$arrayChangePassword[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayChangePassword[1] = "┼╙у ╟с═╙╟╚ ╟су╙╩╬╧у █э╤ ╒═э═";
$arrayChangePassword[2] = "▀су╔ ╟су╤ц╤ ╟с╬╟╒╔ ╚╟с═╙╟╚ █э╤ ╒═э═╔";
$arrayChangePassword[3] = "╩у╩ ┌усэ╔ ╩█ээ╤ ▀су╔ ╟су╤ц╤ ╚ф╠╟═";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ┼╙╩╤╠╟┌ ▀су╔ ╟су╤ц╤
$arrayForgetPassword = array();
$arrayForgetPassword[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayForgetPassword[1] = "┼╙у ╟с═╙╟╚ ╟су╙╩╬╧у █э╤ ╒═э═";
$arrayForgetPassword[2] = "╟с┼эуэс ╟с╬╟╒ ╚╟с═╙╟╚ █э╤ у╩ц▌╤";
$arrayForgetPassword[3] = "╩у ┼╤╙╟с ▀су╔ ╟су╤ц╤ ┌сь ╟с╠ц╟с ╚ф╠╟═";
$arrayForgetPassword[4] = "╤╒э╧▀ █э╤ ▀╟▌э с┼╩у╟у ┌усэ╔ ╟с┼╤╙╟с";
$arrayForgetPassword[5] = "╩у ┼╤╙╟с ▀су╔ ╟су╤ц╤ ┌сь ╟с┼эуэс ╚ф╠╟═";
$arrayForgetPassword[6] = "╟с┼эуэс ╟с╬╟╒ ╚╟с═╙╟╚ █э╤ ╒═э═";
$arrayForgetPassword[7] = "┼╙у ╟с═╙╟╚ ╟су╙╩╬╧у █э╤ ╒═э═";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╟с┼╤╙╟с
$arraySendMsg = array();
$arraySendMsg[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arraySendMsg[1] = "╩у╩ ┌усэ╔ ╟с┼╤╙╟с ╚ф╠╟═";
$arraySendMsg[2] = "╤╒э╧▀ 0,╟с╤╠╟┴ ┼┌╟╧╔ ╟с╩┌╚╞╔ ═╩ь ╩╩у▀ф уф ┼╤╙╟с ╟с╤╙╟╞с";
$arraySendMsg[3] = "╤╒э╧▀ █э╤ ▀╟▌э с┼╩у╟у ┌усэ╔ ╟с┼╤╙╟с";
$arraySendMsg[4] = "┼╙у ╟с═╙╟╚ ╟су╙╩╬╧у █э╤ ╒═э═";
$arraySendMsg[5] = "▀су╔ ╟су╤ц╤ ╟с╬╟╒╔ ╚╟с═╙╟╚ █э╤ ╒═э═╔";
$arraySendMsg[6] = "╒▌═╔ ╟с╟ф╩╤ф╩ █э╤ ▌┌╟с╔,═╟цс ╟с╟╤╙╟с уф ╠╧э╧";
$arraySendMsg[7] = "ф┘╟у ╟су╧╟╤╙ █э╤ ▌┌╟с";
$arraySendMsg[8] = "╩▀╤╟╤ ╤у╥ ╟су╧╤╙╔ сф▌╙ ╟су╙╩╬╧у";
$arraySendMsg[9] = "╟ф╩х╟┴ ╟с▌╩╤╔ ╟с╩╠╤э╚э╔";
$arraySendMsg[10] = "┌╧╧ ╟с╟╤▐╟у с╟ э╙╟цэ ┌╧╧ ╟с╤╙╟╞с";
$arraySendMsg[11] = "╟╘╩╤╟▀▀ с╟ э╩э═ с▀ ╟╤╙╟с ╤╙╟╞с сх╨х ╟су╧╤╙╔. э╠╚ ┌сэ▀ ╩▌┌эс ╟с╟╘╩╤╟▀ сх╨х ╟су╧╤╙╔";
$arraySendMsg[12] = "┼╒╧╟╤ ╟с╚ц╟╚╔ █э╤ ╒═э═";
$arraySendMsg[13] = "╟с╤▐у ╟су╤╙с ╚х █э╤ у▌┌с ├ц с╟ эц╠╧ ╟с╤у╥ BS ▌э фх╟э╔ ╟с╤╙╟с╔";
$arraySendMsg[14] = "█э╤ у╒╤═ с▀ ╚╟с┼╤╙╟с ╚┼╙╩╬╧╟у х╨╟ ╟су╤╙с";
$arraySendMsg[15] = "╟с├╤▐╟у ╟су╤╙с сх╟ █э╤ уц╠ц╧х ├ц █э╤ ╒═э═х";
$arraySendMsg[16] = "┼╙у ╟су╤╙с ▌╟╤█б ├ц █э╤ ╒═э═";
$arraySendMsg[17] = "ф╒ ╟с╤╙╟с╔ █э╤ у╩ц▌╤ ├ц █э╤ у╘▌╤ ╚╘▀с ╒═э═";

$arrayDeleteSMS = array();
$arrayDeleteSMS[1] = "╩у╩ ┌усэ╔ ╟с═╨▌ ╚ф╠╟═";
$arrayDeleteSMS[2] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayDeleteSMS[3] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";
$arrayDeleteSMS[4] = "╟с┼╤╙╟сэх ╟су╪сц╚ ═╨▌х╟ █э╤ у╩ц▌╤хб ├ц ╤▐у deleteKey ╬╟╪╞";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╪с╚ ╟с╤╒э╧
$arrayBalance = array();
$arrayBalance[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayBalance[1] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayBalance[2] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";
$arrayBalance[3] = "╤╒э╧▀ ╟с═╟сэ хц %s ф▐╪х уф ╟╒с %s ф▐╪╔";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╟с╩═▐▐ уф ╪с╚ ┼╙у ╟су╤╙с - ╟с├═╤▌ ╟сх╠╟╞э╔
$arrayCheckAlphasSender = array();
$arrayCheckAlphasSender[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayCheckAlphasSender[1] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayCheckAlphasSender[2] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╪с╚ ┼╙у ╟су╤╙с - ╟с├═╤▌ ╟сх╠╟╞э╔
$arrayAddAlphaSender = array();
$arrayAddAlphaSender[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayAddAlphaSender[1] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayAddAlphaSender[2] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";
$arrayAddAlphaSender[3] = "╪цс ╟╙у ╟су╤╙с ╟су╪сц╚ ├▀╚╤ уф 11 ╬╟ф╔";
$arrayAddAlphaSender[4] = "╩у ┼╓╟▌╔ ╟с╪с╚ ╚ф╠╟═";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╪с╚ ┼╙у ╟су╤╙с - ╤▐у ╟с╠ц╟с
$arrayAddSender = array();
$arrayAddSender[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayAddSender[1] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayAddSender[2] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";
$arrayAddSender[3] = "┼╙у ╟су╤╙с '╟с╤▐у ╟с╧цсэ' █э╤ ╒═э═";
$arrayAddSender[4] = "┼╙у ╟су╤╙с с╟ э═╩╟╠ ┼сь ╩▌┌эс ! ";
$arrayAddSender[5] = "╤╒э╧▀ █э╤ ▀╟▌э с┼╤╙╟с ▀ц╧ ╟с╩▌┌эс";
$arrayAddSender[6] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╟с╩═▐▐ уф ╪с╚ ╩▌┌эс ┼╙у ╟су╤╙с - ╤▐у ╠ц╟с
$arrayCheckSender = array();
$arrayCheckSender[0] = "╟╙у ╟су╤╙с █э╤ у▌┌с";
$arrayCheckSender[1] = "┼╙у ╟су╤╙с у▌┌с";
$arrayCheckSender[2] = "┼╙у ╟су╤╙с у╤▌ц╓";
$arrayCheckSender[3] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayCheckSender[4] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ╩▌┌эс ╪с╚ ┼╙у ╟су╤╙с - ╤▐у ╠ц╟с
$arrayActiveSender = array();
$arrayActiveSender[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arrayActiveSender[1] = "╤▐у ╟с╠ц╟с █э╤ ╒═э═";
$arrayActiveSender[2] = "▀су╔ ╟су╤ц╤ █э╤ ╒═э═х";
$arrayActiveSender[3] = "╩у ╩▌┌эс ┼╙у ╟су╤╙с";
$arrayActiveSender[4] = "▀ц╧ ╟с╩▌┌эс █э╤ ╒═э═";
$arrayActiveSender[5] = "senderId ╬╟╪╞";

//╟с╤╙╟╞с ╟сф╟╩╠х уф ╧╟с╔ ▐╟с╚ ╟с┼╤╙╟с
$arraySendMsgWK = array();
$arraySendMsgWK[0] = "су э╩у ╟с╟╩╒╟с ╚╟с╬╟╧у";
$arraySendMsgWK[1] = "╩у╩ ┌усэ╔ ╟с┼╤╙╟с ╚ф╠╟═";
$arraySendMsgWK[2] = "╤╒э╧▀ 0,╟с╤╠╟┴ ┼┌╟╧╔ ╟с╩┌╚╞╔ ═╩ь ╩╩у▀ф уф ┼╤╙╟с ╟с╤╙╟╞с";
$arraySendMsgWK[3] = "╤╒э╧▀ █э╤ ▀╟▌э с┼╩у╟у ┌усэ╔ ╟с┼╤╙╟с";
$arraySendMsgWK[4] = "┼╙у ╟с═╙╟╚ ╟су╙╩╬╧у █э╤ ╒═э═";
$arraySendMsgWK[5] = "▀су╔ ╟су╤ц╤ ╟с╬╟╒╔ ╚╟с═╙╟╚ █э╤ ╒═э═╔";
$arraySendMsgWK[6] = "╒▌═╔ ╟с╟ф╩╤ф╩ █э╤ ▌┌╟с╔,═╟цс ╟с╟╤╙╟с уф ╠╧э╧";
$arraySendMsgWK[7] = "ф┘╟у ╟су╧╟╤╙ █э╤ ▌┌╟с";
$arraySendMsgWK[8] = "╩▀╤╟╤ ╤у╥ ╟су╧╤╙╔ сф▌╙ ╟су╙╩╬╧у";
$arraySendMsgWK[9] = "╟ф╩х╟┴ ╟с▌╩╤╔ ╟с╩╠╤э╚э╔";
$arraySendMsgWK[10] = "┌╧╧ ╟с╟╤▐╟у с╟ э╙╟цэ ┌╧╧ ╟с╤╙╟╞с";
$arraySendMsgWK[11] = "╟╘╩╤╟▀▀ с╟ э╩э═ с▀ ╟╤╙╟с ╤╙╟╞с сх╨х ╟су╧╤╙╔. э╠╚ ┌сэ▀ ╩▌┌эс ╟с╟╘╩╤╟▀ сх╨х ╟су╧╤╙╔";
$arraySendMsgWK[12] = "┼╒╧╟╤ ╟с╚ц╟╚╔ █э╤ ╒═э═";
$arraySendMsgWK[13] = "╟с╤▐у ╟су╤╙с ╚х █э╤ у▌┌с ├ц с╟ эц╠╧ ╟с╤у╥ BS ▌э фх╟э╔ ╟с╤╙╟с╔";
$arraySendMsgWK[14] = "█э╤ у╒╤═ с▀ ╚╟с┼╤╙╟с ╚┼╙╩╬╧╟у х╨╟ ╟су╤╙с";
$arraySendMsgWK[15] = "╟с├╤▐╟у ╟су╤╙с сх╟ █э╤ уц╠ц╧х ├ц █э╤ ╒═э═х";
$arraySendMsgWK[16] = "┼╙у ╟су╤╙с ▌╟╤█б ├ц █э╤ ╒═э═";
$arraySendMsgWK[17] = "ф╒ ╟с╤╙╟с╔ █э╤ у╩ц▌╤ ├ц █э╤ у╘▌╤ ╚╘▀с ╒═э═";
?>