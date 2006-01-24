<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Swedish/admin/lan_upload.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-01-24 12:44:19 $
|     $Author: mrpiercer $
+----------------------------------------------------------------------------+
*/
define("UPLLAN_1", "Uppladdning borttagen från lista.");
define("UPLLAN_2", "Inställningar sparade i databasen");
define("UPLLAN_3", "UppladdningsID");

define("UPLLAN_5", "Postare");
define("UPLLAN_6", "E-post");
define("UPLLAN_7", "Webbsajt");
define("UPLLAN_8", "Filnamn");

define("UPLLAN_9", "Version");
define("UPLLAN_10", "Fil");
define("UPLLAN_11", "Filstorlek");
define("UPLLAN_12", "Skärmbild");
define("UPLLAN_13", "Beskrivning");
define("UPLLAN_14", "Demo");

define("UPLLAN_16", "kopiera till nyhetspost");
define("UPLLAN_17", "ta bort uppladdning från lista");
define("UPLLAN_18", "Visa detaljer");
define("UPLLAN_19", "Det finns inga omodererade publika uppladdade filer");
define("UPLLAN_20", "Det");
define("UPLLAN_21", "omodererade publika uppladdningar");
define("UPLLAN_22", "ID");
define("UPLLAN_23", "Namn");
define("UPLLAN_24", "Filtyp");
define("UPLLAN_25", "Uppladdningar aktiverade?");
define("UPLLAN_26", "Inga publika uppladdningar tillåts om inaktiverad");

define("UPLLAN_29", "Lagringstyp");
define("UPLLAN_30", "Välj hur uppladdade filer skall sparas, antingen som normala filer på servern eller som binär info i databasen<br /><b>OBS</b> binärt är bara lämpligt för mindre filer under cirka 500 kB");
define("UPLLAN_31", "Fil");
define("UPLLAN_32", "Binärt");
define("UPLLAN_33", "Maximal filstorlek");
define("UPLLAN_34", "Maximal filstorlek i bytes - lämna tomt för att använda inställningen i php.ini ( php.ini är satt till");
define("UPLLAN_35", "Tillåtna filtyper");
define("UPLLAN_36", "Angen en typ per rad");
define("UPLLAN_37", "Behörighet");
define("UPLLAN_38", "Välj för att endast tillåta vissa användare att ladda upp");
define("UPLLAN_39", "Skicka");

define("UPLLAN_41", "Observera - filuppladdningar är inaktiverade i din php.ini, filer kommer inte att kunna laddas upp förrän du satt det till On i php.ini.");

define("UPLLAN_42", "Åtgärder");
define("UPLLAN_43", "Uppladdningar");
define("UPLLAN_44", "Uppladdning");
define("UPLLAN_45", "Är du säker på att du vill radera följande fil...");

define("UPLAN_COPYTODLM", "kopiera till nerladdningshanteraren");
define("UPLAN_IS", "är ");
define("UPLAN_ARE", "är "); //Pluralis
define("UPLAN_COPYTODLS", "Kopiera till nerladdning");

define("UPLLAN_48", "Av säkerhetsskäl har tillåtna filtyper flyttats ut från databasen till en textfil som finns i din admin katalog.
För att använda den, döp om filen e107_admin/filetypes_.php till e107_admin/filetypes.php och lägg till en kommaseparerad lista av
filtyp-ändelser i filen. Du skall inte tillåta uppladdning av .html, .txt, etc eftersom någon som attackerar din sajt kan ladda
en fil av denna typ som innehåller farliga javascript. Du skall, självklart, heller inte tillåta uppladdning av .php filer eller
någon annan typ av exekverbart skript.");

?>