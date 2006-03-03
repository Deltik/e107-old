<?php
$caption = "Fórum súgó";
$text = "<b>Általános</b><br />
Fórumok létrehozása, módosítása<br />
<br />
<b>Főkategóriák/Fórumok</b><br />
A főkategóriák tényleges fórumokat tartalmazzák, ezzel egyszerűbb az elrendezés és a fórumok közötti navigáció.
<br /><br />
<b>Elérhetőség</b>
<br />
Beállíthatod, hogy csak bizonyos látogatók érhessék el a fórumokat. Ha beállítod a látogatók 'csoportját', a kijelöléssel csak ezen csoport tagjai érhetik el a fórumot. Főkategóriát és egyéni fórumot is korlátozhatsz így.
<br /><br />
<b>Moderátorok</b>
<br />
Jelöld be a felsorolt adminisztrátorok nevénél, hogy fórum moderátor legyen. Az adminisztrátornak fórum moderálási jogosultságokkal kell rendelkeznie, hogy megjelenjen a listában.
<br /><br />
<b>Rangok</b>
<br />
Itt állíthatod be a rangokat. Ha a kép mezők ki vannak töltve, képek lesznek használva, a rang nevek használatához írd be azokat, és győződj meg róla, hogy a hozzá tartozó rang kép mező üres.<br />A határ az a pontszám, amelynek elérésével a tag az adott rangot megkapja.";
$ns -> tablerender($caption, $text);
unset($text);
?>
