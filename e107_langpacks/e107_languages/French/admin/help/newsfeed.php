<?php
/*
+---------------------------------------------------------------+
| Fichiers de langage Français e107 CMS (utf-8). Licence GNU/GPL
| Traducteurs: communauté francophone e107
|     $Source: /cvs_backup/e107_langpacks/e107_languages/French/admin/help/newsfeed.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-12-04 21:32:31 $
|     $Author: daddycool78 $
+---------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

$text = "Vous pouvez récupérer et 'parser'les RSS d'autres sites et les afficher sur votre site à partir d'ici.<br />Entrez l'url complète vers le backend (par exemple http://e107.org/news.xml). Vous pouvez spécifier l'adresse vers une image si vous n'aimez pas celle par défaut. Vous pouvez activer ou désactiver le backend si le site est indisponible pendant un moment.<br /><br />Pour voir les titres sur votre site, soyez sur que le menu 'headlines_menu'est activé dans la section des menus.";
$ns -> tablerender("Aide", $text);
?>
