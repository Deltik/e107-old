<?php

/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: 
|     $Revision: 
|     $Date: 
|     $Author: 
|			
+----------------------------------------------------------------------------+
*/
$caption = "Kullan�c�y� Sitenizden uzakla�t�rma (BANlama)";
$text = "Bu Men� �zerinden kullan�c�lar� sitenizden uzakla�t�rabilirsiniz (BAN).<br />
Ya bir ki�i i�in IP adresiniz girerek , yada bir�ok ki�iyi (wildcard) rastgele BANlayabilirsiniz. Yada belli E.Mail adreslerini girerek istemedi�iniz ki�ilerin sitenize kay�t  olmas�n� engelleyebilirsiniz.<br /><br />
<b> IP-Adresi �zeri BANlama:</b><br />
�rnek olarak mesela 123.123.123.123 IP-Adresini verirseniz , bu IP kullanan ki�iler yasaklan�r (BANlan�r).<br />
Ama siz �imdi  123.123.123.* IP-Adresini  verirseniz, o zaman sonu 000 - 999 ile biten t�m  IP adresi kullan�c�lar� BANlan�r. Yan� bir ka� de�i�ik kullan�c� yasaklanm�� olacak.<br /><br />
<b>E-Mail Adresi  �zeri BANlama:</b><br />
Mesela  isim@email.com adresini verirseniz, ozaman bu E-Mail adresini kullanan ki�i sitenize kay�t olmas� engellenmi� olur. Bu genellikle bir ki�i yada bir gruba ait kay�t yapmaya �al��an ekip olabilir.<br />
�ayet �imdi  *@email.com adresini girerseniz, o zaman bu domaine ait emaili olan K�MSE kay�t olamaz! O y�zden yasaklama i�leminde dikkatli olunuz!.";
$ns -> tablerender($caption, $text);
?>
