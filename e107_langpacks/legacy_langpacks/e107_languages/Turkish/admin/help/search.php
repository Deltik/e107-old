<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "�ayet MySql server versiyonunuz bunu destekliyorsa, siz o zaman  
 daha h�zl� olan MySql sort(ay�klama) metodu ile   PHP sort(ay�klama) metodu aras�nda se�im yapabilirsiniz. Ayarlara bak�n�z.<br /><br />
�ayet sitenizde  Ideographic languages (dil) i�erikli ise, yani �ince yada Japonca o zaman 
 PHP sort metodunu kullanmal�s�n�z ve   whole word matching off  se�melisiniz.";
$ns -> tablerender("Arama  Zard�m", $text);
?>
