<?php
$text = "Bu Men� �zerinden,  Kendinize �zel sayfalar ve Men�ler  yapabilirsiniz.<br /><br />
<b>�nemli Bilgi!</b><br />
- Bu Ekstralar� kullanabilmek i�in  /e107_plugins/custom/ ve /e107_plugins/custompages/ dosyas� yetkisini  CHMOD  777 yapmal�s�n�z.
<br />
- Bunun i�in HTML Kodlamalar�n�da kullanabilirsiniz. Dikkat edece�iniz �nemli konu, ( " )  yerine ( ' ) kullanman�zd�r !! . "/  kulland���n�z taktirde mizampajda hata olu�acakt�r!
<br /><br />
<i>Men�/Sayfa-Dosya ismi</i>: Yapt���n�z Men� ve sayfalar ".e_PLUGIN."custom/ Klas�r�nde  'Siteninismi.php' olarak kay�t olacakt�r.<br />
Sayfa kendisi ".e_PLUGIN."custompages/ Klas�r�ndeki Siteninismi.php dosyas�na kay�t olacakt�r<br /><br />
<i>Men�/Sayfa Ba�l���<i/>: Yaz� Metni, Ba�l�k olarak Men�ler ve Sayfa ba�l���nda g�sterilecektir.<br /><br />
<i>Men�/Sayfa yaz�s�(i�eri�i)</I>: Sizin girmi� oldu�unuz bilgiler, ya 'BODY' b�lgesine yada normal yaz� olarak verilecektir. �a��rmak i�in her seferinde, class2.php,   HEADER ve FOOTERE, sat�rlar� eklemenize gerek yoktur.Bu sat�rlar otomatik olarak eklenecektir.<br /> Yinede sayfan�zda de�i�iklikler yapmak isterseniz, bunu CSS bi�em dosyas� �zerinden s�n�fland�rarak sayfan�z�n de�i�ik g�r�nmesini sa�layabilirsiniz.";
$ns -> tablerender(CUSLAN_18, $text);
?>
