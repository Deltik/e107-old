<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Notify/hat�rlat�c� 107 de girilmi� olan olaylar� hat�rlatmak i�in size email yollar.<br /><br />
�rnek olarak:, �ayet  'IP adresi flood yapt��� i�in banland�. ' SEL/ FLOOD G�velik ayar� a��kken, sitenize Flood / �o�ul  sald�r� olursa,'Admin' ve t�m  Y�netici s�n�f�ndaki �yelere email g�nderilecektir.<br /><br />
Ba�ka bir �rnek olarak  'News item posted by admin= admin taraf�ndan haber yolland�' ayar�n� '�yelerinize' ve kullan�c�lar�n�za a�arsan�z, o zaman haber yollad���n�z taktirde, onlara otomatik olarak email yollanacakt�r.<br /><br />
�ayet email Notifcation/Hat�rlatmay�  alternatif olarak ba�ka bir email adresine yollamak istiyorsan�z � o zaman 'Email' opsiyonunu se�ip yollamak istedi�iniz email adresini giriniz.";

$ns -> tablerender("Notify/Hat�rlat�c� Yard�m", $text);
?>
