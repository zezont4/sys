<?php
include("includeSettings.php");			//םÍÊזם וÐו ÇבÇבדבÝ Úבל ÌדםÚ ÅÚÏÇÏÇÊ ÇבÅÑÓÇב ÇבÑÆםÓםו
$mobile = ""; 							//ÇÓד ÇבדÓÊÎÏד דה דזÈÇםבם
$password = "";							//ÇבÈÇÓזÑÏ  דה דזÈÇםבם
$senderId = "";							//ÇבÞםדÉ ÇבÚÏÏםו ÇבהÇÊÌו דה ÚדבםÉ ØבÈ ÊÝÚםב ÑÞד ÇבÌזÇב ‗ÅÓד דÑÓב¡ זÈÏזה ÇבÑדÒ (#)¡ ז‗דËÇב ÝÅה ÇבÑÞד #110 םÌÈ ÅÑÓÇבו Úבל ÇבÔ‗ב 110

$resultType = 0;						//ÏÇבÉ ÊÍÏםÏ הזÚ ÇבהÊםÌו ÇבÑÇÌÚו דה ÇבÈזÇÈÉ
										//0: ÅÑÌÇÚ ÇבהÊםÌו ‗דÇ ום Ýם ÇבÈזÇÈÉ
										//1: ÅÑÌÇÚ דÚהל ÇבהÊםÌו ÇבÑÇÌÚו דה ÇבÈזÇÈÉ	

// ÏÇבÉ ÇבÊÍÞÞ דה  ÊÝÚםב ÑÞד ÇבÌזÇב ‗ÅÓד דÑÓב
echo checkSender($mobile,$password,$senderId,$resultType);
?>