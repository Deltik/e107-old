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
$text = "Bu sayfa �zerinden Tekil yada �o�ul Makale olu�turabilirsiniz.<br />
 �ok sayfal� makale kullanmak i�in her sayfay� bir BBCode ile ay�rmal�s�n�z. <br />
 Bunun i�in l�tfen SADECE ! bu TAG kullan�n: [newpage], �rnek olarak: <br />
 <code>Test Sayfa 1 [newpage] Test sayfa 2</code><br /> B�yle bir yaz� metni olan �ki sayfal�k bir Makale yazmak  i�in 1. sayfaya:<br />
 'Test sayfa 1' ve devam eden 2. sayfa i�eri�i: <br />
 'Test sayfa 2'.
<br /><br />
�ayet HTML Taglar i�eren  bir makaleyi kullanmak istiyorsan�z, o zaman bu Taglar�  bu Kodlarla �evrelemelisiniz: [html] [/html].<br />
�rnek olarak bu yaz�y� verdik: '&lt;table>&lt;tr>&lt;td>Merhaba &lt;/td>&lt;/tr>&lt;/table>' �imdi sizin makalenizde i�inde Merhaba diye bir Tablo olu�ur.<br />
Ama siz �imdi ��yle yazarsan�z '[html]&lt;table>&lt;tr>&lt;td>Merhaba  &lt;/td>&lt;/tr>&lt;/table>[/html]' , o zaman Kodlarla (code)�retilen tablo olarak de�ilde, sizin yazd���n�z Kod (code)  gibi g�z�k�r.";
$ns -> tablerender("Makale  Yard�m", $text);
?>
