<?php
/*
+---------------------------------------------------------------+
| Fichiers de langage Français e107 CMS (utf-8). License GNU/PGL
| Traducteurs: communauté francophone e107
|     $Source: /cvs_backup/e107_langpacks/e107_languages/French/admin/help/custommenu.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-10-27 14:43:41 $
|     $Author: daddycool78 $
+---------------------------------------------------------------+
*/
  $text = "À partir de cette page, vous pouvez créer des menus personnifiés avec votre propre contenu.<br /><br /><strong>Attention</strong> : pour utiliser cette fonctionnalité, vous devez faire un CHMOD de 777 sur le dossier /e107_menus/custom/.
  <br /><br />
  <i>Nom de fichier</i> : le nom du menu, le menu sera sauvegardé sous le nom ';votre_nom.php'; dans le dossier des menus<br />
  <i>Titre</i> : le texte à afficher dans la barre de titre du menu<br />
  <i>Texte</i> : les données qui seront affichées dans le menu, peuvent être du texte, des images ...";
  $ns -> tablerender("Aide", $text);
  ?>
