<?php
/*
+---------------------------------------------------------------+
|        e107 website system  Language File
|
|        $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Danish/admin/help/banlist.php,v $
|        $Revision: 1.2 $
|        $Date: 2006-01-10 16:31:21 $
|        $Author: e107dk $
+---------------------------------------------------------------+
*/








if (!defined('e107_INIT')) { exit; }

$caption = "Banlyse brugere fra dit site";
$text = "Du kan Banlyse/Bortvise brugere fra dit site, p&aring; denne side.<br />
Skriv hele deres IP addresse eller brug et wildcard til at banne en r&aelig;kke af IP addresser. Du kan ogs&aring; skrive en e-mail addresse til at forhindre en bruger i at registrere sig som medlem p&aring; dit site.<br /><br />
<b>Banne p&aring; IP addresse:</b><br />
Ved at skrive IP addressen 123.123.123.123 vil forhindre brugeren med den addresse i at bes&oslash;ge dit site.<br />
Ved at skrive IP addressen 123.123.123.* vil forhindre alle i den IP r&aelig;kke/gruppe i at bes&oslash;ge dit site.<br /><br />
<b>Banne p&aring; e-mail addresse</b><br />
Ved at skrive e-mail addressen skod@nar.dk vil forhindre alle der bruger den e-mail addresse i at registrere sig som medlem p&aring; dit site.<br />
Ved at skrive e-mail addressen *@nar.dk vil forhindre alle der med e-mail fra det dom&aelig;ne i at registrere sig som medlem p&aring; dit site.";
$ns -> tablerender($caption, $text);
?>