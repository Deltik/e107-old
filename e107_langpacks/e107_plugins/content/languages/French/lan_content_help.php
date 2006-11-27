<?php
/*
+---------------------------------------------------------------+
| Fichiers de langage Français e107 CMS (utf-8). License GNU/PGL
| Traducteurs: communauté francophone e107
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/French/lan_content_help.php,v $
|     $Revision: 1.5 $
|     $Date: 2006-11-27 03:07:32 $
|     $Author: daddycool78 $
+---------------------------------------------------------------+
*/
  define("CONTENT_ADMIN_HELP_1", "Aide Gestion de Contenu");
  define("CONTENT_ADMIN_HELP_ITEM_1", "<i>Si vous n'avez pas encore ajouté de catégorie principale, svp faites le à la page <a href='".e_SELF."?cat.create'>Créer une nouvelle catégorie</a>.</i><br /><br /><strong>catégorie</strong><br />sélectionnez une catégorie à partir du menu déroulent pour contrôler le contenu de cette catégorie.<br /><br />La sélection d'une catégorie principale à partir du menu déroulent affichera tous les éléments de contenus de cette catégorie principale.<br />La sélection d'une sous-catégorie affichera uniquement les éléments de contenus de la sous-catégorie indiquée.<br /><br />Vous pouvez également employer le menu du côté droit pour consulter tous les éléments contenus dans la catégorie indiquée.");
  define("CONTENT_ADMIN_HELP_ITEM_2", "<strong>Choix Premières lettres</strong><br />Si de  multiples contenus existent, vous verrez s'afficher des boutons pour selectionner uniquement par la première lettre ces articles de contenus. La sélection du bouton 'all'  affichera une liste contenant tous les articles contenus de cette catégorie. <br /><br /><strong> Liste détaillée </strong> <br / > Vous verrez une liste de tous les articles contenus avec leurs id(identifiants), icônes, auteurs, titres [sous-titres] et leurs options.<br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur <br />".CONTENT_ICON_LINK." :  lien vers  le contenu <br />".CONTENT_ICON_EDIT." : édition du contenu <br />".CONTENT_ICON_DELETE." : suppression du contenu  <br />");
  define("CONTENT_ADMIN_HELP_ITEMEDIT_1", "<strong>Formulaire d'édition</strong><br />Vous pouvez maintenant éditer toutes les informations concernant ce contenu et soumettre vos modifications.<br /><br />Si vous deviez changer la catégorie de ce contenu, veuillez le faire en premier. Après avoir choisi la catégorie correcte, modifier ou ajoutez un champ présent souhaité, avant de soumettre toutes ces modifications.");
  define("CONTENT_ADMIN_HELP_ITEMCREATE_1", "<strong>Catégorie</strong><br />Veuillez sélectionner une catégorie dans la case de sélection pour créer votre contenu.<br />");
  define("CONTENT_ADMIN_HELP_ITEMCREATE_2", "<strong>Formulaire de création </strong><br />Vous pouvez maintenant fournir toute l'information pour ce contenu et le soumettre.<br /><br />Soyez conscient du fait que des catégories principales différentes peuvent avoir un jeu de préférences; de champs différents restant à votre disposition afin d'être renseignés. C'est pourquoi vous devez donc toujours choisir une catégorie en premier avant d'avoir à remplir d'autres champs!");
  define("CONTENT_ADMIN_HELP_CAT_1", "<i>Cette page affiche toutes les catégories et sous-catégories présentes.</i><br /><br /><strong>Liste détaillée </strong><br />vous verrez une liste de toutes les sous-catégories avec leur id, icône, auteur, catégorie [sous-titre] et leurs  options<br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur <br />".CONTENT_ICON_LINK." : lien vers la catégorie<br />".CONTENT_ICON_EDIT." : éditer la catégorie<br />".CONTENT_ICON_DELETE." : supprimer la catégorie<br />");
  define("CONTENT_ADMIN_HELP_CAT_2", "<i>Cette page vous permet de créer une nouvelle catégorie</i><br /><br />Choisissez toujours la catégorie parente avant de remplir d'autres champs!<br /><br />Cela doit être fait, car des préférences uniques à certaines catégories doivent être chargées dans le système.<br /><br />Par défaut, vous créez une nouvelle catégorie principale.");
  define("CONTENT_ADMIN_HELP_CAT_3", "<i>Cette page affiche le formulaire d'édition des catégories.</i><br /><br /><strong>formulaire édition catégorie </</strong><br />vous pouvez maintenant éditer toutes les informations pour cette (sous) catégorie et soumettre vos changements.<br /><br /> Si vous voulez changer l'adresse de référence mère de cette catégorie, faites le s'il vous plaît d'abord. Après avoir mis en place la catégorie correcte éditer tous les autres champs.");
  define("CONTENT_ADMIN_HELP_ORDER_1", "<i>Cette page affiche toutes catégories et sous-catégories présentes.</i><br /><br /><strong>Liste détaillée </strong><br />Vous pouvez voir l'id (identifiant) et le nom de la catégorie. Vous pouvez aussi voir que plusieurs options gèrent l'ordre des catégories.<br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur<br />".CONTENT_ICON_LINK." : lien vers la catégorie<br />".CONTENT_ICON_ORDERALL." : Gérez l'ordre global du contenu indépendamment de la catégorie. .<br />".CONTENT_ICON_ORDERCAT." : Gérez l'ordre du contenu dans la catégorie spécifique .<br />".CONTENT_ICON_ORDER_UP." : le bouton 'vers le haut' vous permet de déplacer un contenu vers le haut.<br />".CONTENT_ICON_ORDER_DOWN." : le bouton 'vers le le bas' vous permet de déplacer un contenu vers le bas.<br /><br /><strong>Ordre</strong><br />Ici, vous pouvez manuellement ordonner toutes les catégories de chaque catégorie parente. Vous devez changer les valeurs dans les boîtes de selection pour ordonner selon vos préférences et appuyer ensuite sur le bouton de mise à jour au-dessous pour sauvegarder le nouvel ordre.<br />");
  define("CONTENT_ADMIN_HELP_ORDER_2", "<i>Cette page affiche tous les articles de contenus de la catégorie que vous avez sélectionnée.</i><br /><br /><strong>Liste détaillée </strong><br />Vous pouvez voir l'id(identifiant) l'auteur et le titre du contenu. Vous pouvez voir aussi plusieurs options gérant l'ordre des contenus <br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur<br />".CONTENT_ICON_LINK." : lien vers le contenu<br />".CONTENT_ICON_ORDER_UP." : le bouton 'vers le haut' vous permet de déplacer un contenu vers le haut.<br />".CONTENT_ICON_ORDER_DOWN." : : le bouton 'vers le le bas' vous permet de déplacer un contenu vers le bas.<br /><br /><strong> Ordre</strong><br />ici vous pouvez manuellement ordonner toutes les catégories de chaque catégorie mère. Vous devez changer les valeurs dans les boîtes de selection pour ordonnancer suivant vos souhaits et appuyer ensuite sur le bouton de mise à jour ci-dessous pour sauvegarder le nouvel ordonnancement.<br />");
  define("CONTENT_ADMIN_HELP_ORDER_3", "<i>Cette page affiche tous les articles de contenus de la catégorie mère que vous avez sélectionnée.</i><br /><br /><strong>Liste détaillée </strong><br />Vous pouvez voir l'id l'auteur et le titre du contenu. Vous pouvez voir aussi plusieurs options gérant l'ordre des contenus <br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur<br />".CONTENT_ICON_LINK." : lien vers le contenu<br />".CONTENT_ICON_ORDER_UP." : the up button allow you to move a content item one up in order.<br />".CONTENT_ICON_ORDER_DOWN." : the down button allow you to move a content item one down in order.<br /><br /><strong>order</strong><br />here you can manually set the order of all the catégories in this main parent. You need to change the values in the select boxes to the order of your kind and then press the update button below to save the new order.<br />");
  define("CONTENT_ADMIN_HELP_OPTION_1", "Sur cette page vous pouvez séléctionner une catégorie principale pour établir ses options , ou pour choisir d'éditer les préférences par défaut.<br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur<br />".CONTENT_ICON_LINK." : lien vers la catégorie<br />".CONTENT_ICON_OPTIONS." : éditer les options<br /><br /><br />Les préférences par défaut sont seulement employées lorsque vous créez une nouvelle catégorie principale. Ainsi lorsque vous créez une nouvelle catégorie principale, les préférences par défaut seront récupérées. Vous pouvez changer celles-ci pour vous assurer que les catégories principales nouvellement créés ont déjà un certain jeu de caractéristiques présent.<br /><br />Chaque catégorie principale a son propre jeu d'options, qui sont uniques pour cette catégorie spécifique.<br /><br /><b>héritage</b><br />L'option d'héritage vous permet de passer outre les options choisies individuellement de la catégorie principale et d'employer les options par défaut.");
  define("CONTENT_ADMIN_HELP_MANAGER_1", "Sur cette page vous verrez une liste de toutes les catégories. Vous pouvez gérer les options du 'Gestionnaire Personnel de Contenu' pour chacune des catégories en cliquant sur les icônes.<br /><br /><strong>Explications des icônes</strong><br />".CONTENT_ICON_USER." : lien vers le profil de l'auteur<br />".CONTENT_ICON_LINK." : lien vers la catégorie<br />".CONTENT_ICON_CONTENTMANAGER_SMALL." : Lancer le gestionnaire personnel de contenu pour cette catégorie.<br />");
  define("CONTENT_ADMIN_HELP_MANAGER_2", "<i> Sur cette page vous pouvez assigner des groupes d'utilisateurs à la catégorie choisie</i><br /><br /><strong> Gestionnaire personnel de contenu </strong> <br />Vous pouvez définir des groupes d'utilisateurs pour les différents types de gestionnaire personnel de contenu. Il y a présentement 3 types de gestionnaires que vous pouvez définir:<br /><br />Appobation des propositions: les utilisateurs de ce groupe peuvent faire publier les items de contenus proposés<br /><br />Gestionnaire personnel: les utilisateurs de ce groupe ne peuvent gérer que les items de contenus qu'ils ont publiés eux-même<br /><br />Gestionnaire de catégories: les utilisateurs de ce groupe peuvent gérer tous les items de contenus d'une catégorie.");
  define("CONTENT_ADMIN_HELP_SUBMIT_1", "<i> Sur cette page vous verrez une liste de tous les articles de contenus qui ont été proposé par les utilisateurs.</i>
  <br /><br /><strong>Liste détaillée </strong>
  <br />Cette liste de contenus est proposée pour chaque contenus avec l' id(identifiant), l'icône, la catégorie principale, le titre [le sous-titre], l'auteur et ses options.
  <br /><br /><strong>options</strong><br /> 
  vous pouvez poster ou supprimer un contenus en utilisant les boutons affichés.");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_1", "<i>Cette page vous permet d'établir les options pour la création de contenu par les administrateurs (ou via le gestionnaire personnel).</i><br /><br /> Vous pouvez définir quelles options seront disponibles quand un admin veut créer un nouvel élément de contenu.
  <br /><br /><strong>Étiquettes de données personnalisées</strong><br />
  Vous pouvez autoriser à ajouter des champs facultatifs au contenu en utilisant ces étiquettes de données personnalisées. Ces champs facultatifs ont une clef vide = > valeur paire . Par exemple : vous pourriez ajouter un champ clef pour 'photographe' et remplir le champ de valeur 'toutes mes photos'. 
  Tant cette clef, que les champs de valeurs seront des cases à texte qui apparaitront vide dans le formulaire de création.
  <br /><br /><strong>Étiquettes de données prédéfinies</strong><br />En plus des étiquettes de données personnalisées, vous pouvez fournir des étiquettes de données prédéfinies. 
  La différence est que dans les étiquettes de données prédéfinies, on donne le champ clef au départ ainsi l'utilisateur devra uniquement renseigner le champ de valeur pour ce champ prédéfinies. Dans le même exemple que ci-dessus le 'photographe' peut être prédéterminé et l'utilisateur devra remplir 'toutes mes photos'. Vous pouvez choisir le type d'élément en choisissant une option dans la case de sélection. Dans la fenêtre contextuelle (Popup), vous pouvez remplir toute l'information nécessaire à l'étiquette de données prédéfinies.<br />");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_2", "Ces options affectent le contenu proposé par l'utilisateur.<br /><br /> Vous pouvez y définir les options qui seront disponibles lors de la création de contenus à proposer par les utilisateurs.<br /><br />".CONTENT_ADMIN_OPT_LAN_11.":<br />".CONTENT_ADMIN_OPT_LAN_12."");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_3", "Dans ces options, vous pouvez définir où les images et les fichiers sont stockés..<br /><br />Vous pouvez définir quel thème sera employé par cette catégorie principale. Vous pouvez créer des thèmes supplémentaires en copiant (et renommant) la totalité du répertoire 'default' dans votre répertoire de templates.<br /><br />Vous pouvez définir un schéma d'affichage par défaut pour vos contenus. Vous pouvez créer de nouveau schéma d'affichage en créant un fichier content_content_template_<br />XXX.php dans votre répertoire 'templates/default'. Ces schémas d'affichage peuvent être employés pour donner à chaque contenu dans cette catégorie un format d'affichage différent.<br /><br />");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_4", "Ces options sont employés dans toutes les pages de l'extension.");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_5", "<i>Ces options ont un effet sur la section 'Gestionnaire personnel' à l'intérieur de la section d'administration du gestionnaire de contenu.</i><br /><br />".CONTENT_ADMIN_OPT_LAN_63."");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_6", "Ces options sont utilisés dans le Menu de cette catégorie si vous avez activé son menu.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."<br /><br />".CONTENT_ADMIN_OPT_LAN_118.":<br />".CONTENT_ADMIN_OPT_LAN_119."<br /><br />");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_7", "Ces options affectent les détails aperçus des contenus.<br /><br />Cet aperçu est donné sur plusieurs pages, comme la page des nouveautés, la page voir les items d'une catégorie et la page des contenus des auteurs.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_8", "Les pages catégories affichent les informations sur les catégories de contenu qui sont DANS la catégorie principale.<br /><br />Deux zones distinctes sont présentes:<br /><br /><strong>Page liste des catégories :</strong><br />content.php?cat.list.#<br />Cette page affiche toutes les catégories d'un parent (une catégorie principale)<br /><br /><strong>Page aperçu d'une catégorie :</strong><br />content.php?cat.#<br />Cette page affiche tous les items d'une catégorie, optionellement les sous-catégories et leurs items.<br />");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_9", "Les Pages de Contenus montre le contenu.<br /><br />Vous pouvez définir quelles sections seront montrées en cochant/décochant les boîtes.<br /><br />Vous pouvez montrer l'adresse courriel d'un auteur non-utilisateur.<br /><br />Vous pouvez choisir l'affichage ou non des icônes courriel/impression/pdf, de l'évaluation et des commentaires.<br /><br />".CONTENT_ADMIN_OPT_LAN_74."");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_10", "La page Auteur montre une liste de tous les auteurs de contenu de cette catégorie principale.<br /><br />Vous pouvez définir quelles sections seront montrées en cochant/décochant les boîtes.<br /><br />Vous pouvez limiter le nombre d'items à montrer par page.<br />");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_11", "La page Archive montre tous les contenus de cette catégorie principale.<br /><br />Vous pouvez définir quelles sections seront affichées en cochant/décochant les boîtes.<br /><br />Vous pouvez montrer l'adresse courriel d'un auteur non-utilisateur.<br /><br />Vous pouvez limiter le nombre d'items à montrer par page.<br /><br />".CONTENT_ADMIN_OPT_LAN_66."<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_12", "La page <strong>Meilleur Évaluation</strong> affiche tous les contenus qui ont été évalués par les usagers.<br /><br />Vous pouvez définir quelles sections seront affichées en cochant/décochant les boîtes.<br /><br />Vous pouvez montrer l'adresse courriel d'un auteur non-utilisateur.");
  define("CONTENT_ADMIN_HELP_OPTION_DIV_13", "La page des <strong>Meilleurs Qualités</strong> affiche tous les contenus qui ont reçus un score de leur auteur.<br /><br />Vous pouvez définir quelles sections seront affichées en cochant/décochant les boîtes.<br /><br />Vous pouvez montrer l'adresse courriel d'un auteur non-utilisateur.");
define("CONTENT_ADMIN_HELP_OPTION_DIV_14", "cette page vous permet de configurer les options de la page d'administration de création de catégorie.<br /><br />Vous pouvez définir quelles sections seront disponibles lorsqu'un admin (ou un gestionnaire personnel de contenu) crée une nouvelle catégorie de contenu.");
  ?>
