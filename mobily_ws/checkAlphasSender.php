<?php
include("includeSettings.php");			//םÍÊזם וÐו ÇבÇבדבÝ Úבל ÌדםÚ ÅÚÏÇÏÇÊ ÇבÅÑÓÇב ÇבÑÆםÓםו
$mobile = ""; 							//ÇÓד ÇבדÓÊÎÏד דה דזÈÇםבם
$password = "";							//ÇבÈÇÓזÑÏ  דה דזÈÇםבם

$resultType = 0;						//ÏÇבÉ ÊÍÏםÏ הזÚ ÇבהÊםÌו ÇבÑÇÌÚו דה ÇבÈזÇÈÉ
										//0: ÅÑÌÇÚ ÇבהÊםÌו ‗דÇ ום Ýם ÇבÈזÇÈÉ
										//1: ÅÑÌÇÚ דÚהל ÇבהÊםÌו ÇבÑÇÌÚו דה ÇבÈזÇÈÉ	

echo checkAlphasSender($mobile,$password,$resultType);
?>