<?php
include("includeSettings.php");			//םֽÊזם ו׀ו ַבַבדבÝ Úבל ּדםÚ ֵÚַַֿֿÊ ַבֵׁ׃ַב ַבֶׁם׃םו
$mobile = ""; 							//ַ׃ד ַבד׃Ê־ֿד בֽ׃ַָß Ýם דזÞÚ דזַָםבם
$oldPassword = "";						//ßבדֹ ַבדׁזׁ ַבÞֿםדו בֽ׃ַָß Ýם דזÞÚ דזַָםבם
$newPassword = "";						//ßבדֹ ַבדׁזׁ ַבּֿםֿ בֽ׃ַָß Ýם דזÞÚ דזַָםבם
$resultType = 0;						//ַֿבֹ Êֽֿםֿ הזÚ ַבהÊםּו ַבַּׁÚו דה ַבָזַָֹ
										//0: ֵַּׁÚ ַבהÊםּו ßדַ ום Ýם ַבָזַָֹ
										//1: ֵַּׁÚ דÚהל ַבהÊםּו ַבַּׁÚו דה ַבָזַָֹ										

// ַֿבֹ ÊÛםםׁ ßבדֹ ַבדׁזׁ
echo changePassword($mobile,$oldPassword,$newPassword,$resultType);
?>