﻿<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Romanian/lan_content.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-10-23 17:35:39 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

define("CONTENT_ICON_LAN_0", "editare");
define("CONTENT_ICON_LAN_1", "Ştergere");
define("CONTENT_ICON_LAN_2", "opţiuni");
define("CONTENT_ICON_LAN_3", "detalii utilizator");
define("CONTENT_ICON_LAN_4", "descărcare ataşament");
define("CONTENT_ICON_LAN_5", "nou");
define("CONTENT_ICON_LAN_6", "trimitere conţinut");
define("CONTENT_ICON_LAN_7", "lista autorilor");
define("CONTENT_ICON_LAN_8", "atenţie");
define("CONTENT_ICON_LAN_9", "ok");
define("CONTENT_ICON_LAN_10", "eroare");
define("CONTENT_ICON_LAN_11", "ordonare elemente în categorie");
define("CONTENT_ICON_LAN_12", "ordonare elemente în părintele principal");
define("CONTENT_ICON_LAN_13", "administrator personal");
define("CONTENT_ICON_LAN_14", "manager personal de conţinut");

if (!defined('CONTENT_ICON_EDIT')) { define("CONTENT_ICON_EDIT", "<img src='".e_PLUGIN."content/images/maintain_16.png' alt='".CONTENT_ICON_LAN_0."' style='border:0; cursor:pointer;' />"); }
//maintain_16
//edit_16

if (!defined('CONTENT_ICON_DELETE')) { define("CONTENT_ICON_DELETE", "<img src='".e_PLUGIN."content/images/banlist_16.png' alt='".CONTENT_ICON_LAN_1."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_DELETE_BASE')) { define("CONTENT_ICON_DELETE_BASE", e_PLUGIN."content/images/banlist_16.png"); }
if (!defined('CONTENT_ICON_OPTIONS')) { define("CONTENT_ICON_OPTIONS", "<img src='".e_PLUGIN."content/images/cat_settings_16.png' alt='".CONTENT_ICON_LAN_2."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_USER')) { define("CONTENT_ICON_USER", "<img src='".e_PLUGIN."content/images/users_16.png' alt='".CONTENT_ICON_LAN_3."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_FILE')) { define("CONTENT_ICON_FILE", "<img src='".e_PLUGIN."content/images/file_16.png' alt='".CONTENT_ICON_LAN_4."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_NEW')) { define("CONTENT_ICON_NEW", "<img src='".e_PLUGIN."content/images/articles_16.png' alt='".CONTENT_ICON_LAN_5."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_SUBMIT')) { define("CONTENT_ICON_SUBMIT", "<img src='".e_PLUGIN."content/images/redo.png' alt='".CONTENT_ICON_LAN_6."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_AUTHORLIST')) { define("CONTENT_ICON_AUTHORLIST", "<img src='".e_PLUGIN."content/images/personal.png' alt='".CONTENT_ICON_LAN_7."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_WARNING')) { define("CONTENT_ICON_WARNING", "<img src='".e_PLUGIN."content/images/warning_16.png' alt='".CONTENT_ICON_LAN_8."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_OK')) { define("CONTENT_ICON_OK", "<img src='".e_PLUGIN."content/images/ok_16.png' alt='".CONTENT_ICON_LAN_9."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_ERROR')) { define("CONTENT_ICON_ERROR", "<img src='".e_PLUGIN."content/images/error_16.png' alt='".CONTENT_ICON_LAN_10."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_ORDERCAT')) { define("CONTENT_ICON_ORDERCAT", "<img src='".e_PLUGIN."content/images/view_remove.png' alt='".CONTENT_ICON_LAN_11."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_ORDERALL')) { define("CONTENT_ICON_ORDERALL", "<img src='".e_PLUGIN."content/images/window_new.png' alt='".CONTENT_ICON_LAN_12."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_CONTENTMANAGER')) { define("CONTENT_ICON_CONTENTMANAGER", "<img src='".e_PLUGIN."content/images/manager_48.png' alt='".CONTENT_ICON_LAN_14."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_CONTENTMANAGER_SMALL')) { define("CONTENT_ICON_CONTENTMANAGER_SMALL", "<img src='".e_PLUGIN."content/images/manager_16.png' alt='".CONTENT_ICON_LAN_13."' style='border:0; cursor:pointer;' />"); }

define("LAN_38", "votare");
define("LAN_39", "voturi");
define("LAN_40", "cum cotaţi conţinutul?");
define("LAN_41", "mulţumim pentru vot");
define("LAN_65", "necotat");

define("CONTENT_ADMIN_CAT_LAN_0", "creare categorie de conţinut");
define("CONTENT_ADMIN_CAT_LAN_1", "editare categorie de conţinut");
define("CONTENT_ADMIN_CAT_LAN_2", "titlu");
define("CONTENT_ADMIN_CAT_LAN_3", "subtitlu");
define("CONTENT_ADMIN_CAT_LAN_4", "text");
define("CONTENT_ADMIN_CAT_LAN_5", "iconiţă");
define("CONTENT_ADMIN_CAT_LAN_6", "trimitere");
define("CONTENT_ADMIN_CAT_LAN_7", "actualizare");
define("CONTENT_ADMIN_CAT_LAN_8", "vizualizare iconiţe");
define("CONTENT_ADMIN_CAT_LAN_9", "momentan nu există categorii de conţinut");
define("CONTENT_ADMIN_CAT_LAN_10", "categorii de conţinut");
define("CONTENT_ADMIN_CAT_LAN_11", "categorie de conţinut creată");
define("CONTENT_ADMIN_CAT_LAN_12", "categorie de conţinut actualizată");
define("CONTENT_ADMIN_CAT_LAN_13", "câmpuri necesare lăsate necompletate");
define("CONTENT_ADMIN_CAT_LAN_14", "comentarii");
define("CONTENT_ADMIN_CAT_LAN_15", "cotaţie");
define("CONTENT_ADMIN_CAT_LAN_16", "iconiţe e-mail/tipărire");
define("CONTENT_ADMIN_CAT_LAN_17", "vizibilitate");
define("CONTENT_ADMIN_CAT_LAN_18", "autor");
define("CONTENT_ADMIN_CAT_LAN_19", "categorie de conţinut");
define("CONTENT_ADMIN_CAT_LAN_20", "opţiuni");
define("CONTENT_ADMIN_CAT_LAN_21", "ştergere formular");
define("CONTENT_ADMIN_CAT_LAN_22", "opţiuni actualizate");
define("CONTENT_ADMIN_CAT_LAN_23", "categorie de conţinut ştearsã");
define("CONTENT_ADMIN_CAT_LAN_24", "id");
define("CONTENT_ADMIN_CAT_LAN_25", "iconiţă");
define("CONTENT_ADMIN_CAT_LAN_26", "categorie principală nouă");
define("CONTENT_ADMIN_CAT_LAN_27", "categorie");
define("CONTENT_ADMIN_CAT_LAN_28", "Atribuire administratori în managerul personal pentru această categorie");
define("CONTENT_ADMIN_CAT_LAN_29", "Administratori - click pentru a muta ... ");
define("CONTENT_ADMIN_CAT_LAN_30", "Administratori personali pentru această categorie ...");
define("CONTENT_ADMIN_CAT_LAN_31", "eliminare");
define("CONTENT_ADMIN_CAT_LAN_32", "ştergere clasă");
define("CONTENT_ADMIN_CAT_LAN_33", "atribuire administratori pe categorie");
define("CONTENT_ADMIN_CAT_LAN_34", "administratorii au fost atribuiţi cu succes acestei categorii");
define("CONTENT_ADMIN_CAT_LAN_35", "sub-categorie de conţinut creată");
define("CONTENT_ADMIN_CAT_LAN_36", "verificare categorie: încă există sub-categorii, categoria NU va fi ştearsă. Mai întâi ştergeţi toate sub-categoriile, apoi reîncercaţi.");
define("CONTENT_ADMIN_CAT_LAN_37", "verificate elemente de conţinut: încă există elemente de conţinut, categoria NU va fi ştearsă. Mai întâi eliminaţi tot conţinutul, apoi reîncercaţi.");
define("CONTENT_ADMIN_CAT_LAN_38", "verificare elemente de conţinut: nu au fost găsite elemente");
define("CONTENT_ADMIN_CAT_LAN_39", "verificare categorie: nu au fost găsite sub-categorii");
define("CONTENT_ADMIN_CAT_LAN_40", "Mai jos vedeţi o listă a categoriei principale şi a tuturor sub-categoriilor, dacă acestea există.<br />");

define("CONTENT_ADMIN_CAT_LAN_42", "pentru a edita o categorie din categoria principală anterioară");
define("CONTENT_ADMIN_CAT_LAN_41", "Managerul personal al categoriilor de conţinut vă permite să atribuiţi unei categorii anumiţi administratori. Cu acest privilegiu, administratorii îşi pot manevra doar propriile elemente de conţinut în categoria specificată, fără a fi necesar accesul integral la modulul de management al conţinutului. Din exteriorul secţiunii de administrare, aceştia vor putea vedea iconiţa managerului personal de conţinut, care îi va redirecţiona către pagina managerului personal.");

define("CONTENT_ADMIN_CAT_LAN_43", "click aici");
define("CONTENT_ADMIN_CAT_LAN_44", "pentru a adăuga încă o categorie în categoria principală selectată anterior");
define("CONTENT_ADMIN_CAT_LAN_45", "definiţi dacă sunt permise comentariile");
define("CONTENT_ADMIN_CAT_LAN_46", "definiţi dacă este permisă cotarea");
define("CONTENT_ADMIN_CAT_LAN_47", "definiţi dacă ar trebui afişate iconiţele de printare/pdf");
define("CONTENT_ADMIN_CAT_LAN_48", "alegeţi ce utilizatori pot vizualiza acest element");
define("CONTENT_ADMIN_CAT_LAN_49", "alegeţi o iconiţã pentru aceastã categorie");
//define("CONTENT_ADMIN_CAT_LAN_50", "content menu created<br /><br />Because you have created a Main Parent Category, a Menu has been generated.<br />The menu file has been created in your /menus folder.<br /><br />In order to see the menu in action, you still need to activate this menu in your <a href='".e_ADMIN."menus.php'>admin menus area</a>.");
define("CONTENT_ADMIN_CAT_LAN_50", "Va fi creat un fişier meniu doar dacã aţi creat o nouă categorie părinte principală.<br />
Acest fişier-meniu a fost creat în folderul /menus.<br />
Pentru a vedea meniul în acţiune, va trebui să-l activaţi din <a href='".e_ADMIN."menus.php'>zona de administrare a meniurilor</a>.<br /><br />
");
define("CONTENT_ADMIN_CAT_LAN_51", "eroare: fişierul-meniu nu a fost creat");
define("CONTENT_ADMIN_CAT_LAN_52", "ÎNTOTDEAUNA alegeţi o categorie înainte de a completa celelalte câmpuri!");
define("CONTENT_ADMIN_CAT_LAN_53", "părinte principal categorie");

define("CONTENT_ADMIN_OPT_LAN_0", "opţiuni");
define("CONTENT_ADMIN_OPT_LAN_1", "opţiuni administrator: crearea elementelor de conţinut");
define("CONTENT_ADMIN_OPT_LAN_2", "secţiuni");
define("CONTENT_ADMIN_OPT_LAN_3", "alegeţi ce secţiuni vor fi disponibile la crearea unui element de conţinut");//in admin
define("CONTENT_ADMIN_OPT_LAN_4", "iconiţă");
define("CONTENT_ADMIN_OPT_LAN_5", "ataşamente");
define("CONTENT_ADMIN_OPT_LAN_6", "imagini");
define("CONTENT_ADMIN_OPT_LAN_7", "comentariu");
define("CONTENT_ADMIN_OPT_LAN_8", "cotaţie");
define("CONTENT_ADMIN_OPT_LAN_9", "scor");
define("CONTENT_ADMIN_OPT_LAN_10", "iconiţe e-mail/print/pdf");
define("CONTENT_ADMIN_OPT_LAN_11", "vizibilitate");
define("CONTENT_ADMIN_OPT_LAN_12", "meta definiţii");
define("CONTENT_ADMIN_OPT_LAN_13", "taguri de date personalizate");
define("CONTENT_ADMIN_OPT_LAN_14", "definiţi numărul de taguri adiţionale de date");
define("CONTENT_ADMIN_OPT_LAN_15", "cu ajuorul tagurilor personalizate, puteţi crea perechi de date adiţionale, de tipul cheie => valoare, spre a fi stocate pentru un element. de exemplu, dacă doriţi să adăugaţi 'fotografie: de administrator', 'fotografie' este cheia, iar 'de administrator' este valoarea.");
define("CONTENT_ADMIN_OPT_LAN_16", "imagini");
define("CONTENT_ADMIN_OPT_LAN_17", "setaţi numărul de imagini care pot fi uploadate cu un element");
define("CONTENT_ADMIN_OPT_LAN_18", "aceasta va fi folosită doar dacă imaginile sunt activate în zona secţiunilor de mai sus");
define("CONTENT_ADMIN_OPT_LAN_19", "ataşamente");
define("CONTENT_ADMIN_OPT_LAN_20", "setaţi numărul de ataşamente care pot fi uploadate cu un element");
define("CONTENT_ADMIN_OPT_LAN_21", "aceasta va fi folosită doar dacã ataşamentele sunt activate în zona secţiunilor de mai sus");
define("CONTENT_ADMIN_OPT_LAN_22", "trimiteri: opţiuni pentru pagina de trimitere a conţinutului şi formular");
define("CONTENT_ADMIN_OPT_LAN_23", "trimitere elemente");
define("CONTENT_ADMIN_OPT_LAN_24", "Permiteţi trimiterea de elemente");
define("CONTENT_ADMIN_OPT_LAN_25", "dacã activaţi opţiunea, veţi permite vizitatorilor să trimită elemente de conţinut pe site");
define("CONTENT_ADMIN_OPT_LAN_26", "clasă de trimitere elemente");
define("CONTENT_ADMIN_OPT_LAN_27", "alegeţi ce utilizatori pot trimite elemente de conţinut");
define("CONTENT_ADMIN_OPT_LAN_28", "postare directă");
define("CONTENT_ADMIN_OPT_LAN_29", "permiteţi postarea directã a elemetelor de conţinut trimise");
define("CONTENT_ADMIN_OPT_LAN_30", "dacă postarea directă este bifată, elementul trimis este adăugat direct în baza de date şi va fi vizibil imediat. Dacã nu e bifată, administratorul va vedea în zona de administrare un element de conţinut pentru recenzare.");
define("CONTENT_ADMIN_OPT_LAN_31", "defineşte ce secţiuni vor fi disponibile pentru trimiteri");
define("CONTENT_ADMIN_OPT_LAN_32", "alegeţi ce zone permiteţi pentru trimiterea de către utilizatori a elementelor de conţinut");
define("CONTENT_ADMIN_OPT_LAN_33", "locaţii şi căi");
define("CONTENT_ADMIN_OPT_LAN_34", "aici puteţi defini unde sunt imaginile, sau unde vor fi acestea salvate. Folosiţi acolade ( { } ) pentru variabilele generale e107 (precum ( {e_PLUGIN} sau {e_IMAGE} ). Pentu imaginile categoriilor de conţinut, sunt necesare două versiuni: un set mic de imagini şi unul mare.");
define("CONTENT_ADMIN_OPT_LAN_35", "cale : imagini mari categorie");
define("CONTENT_ADMIN_OPT_LAN_36", "definiţi calea către imaginile categoriei (mari)");
define("CONTENT_ADMIN_OPT_LAN_37", "cale: imagini mici categorie");
define("CONTENT_ADMIN_OPT_LAN_38", "definiţi calea către imaginile categoriei (mici)");
define("CONTENT_ADMIN_OPT_LAN_39", "cale iconiţe element");
define("CONTENT_ADMIN_OPT_LAN_40", "definiţi calea către iconiţele elementelor de conţinut");
define("CONTENT_ADMIN_OPT_LAN_41", "cale: imagini element");
define("CONTENT_ADMIN_OPT_LAN_42", "definiţi calea către imaginile elementului de conţinut");
define("CONTENT_ADMIN_OPT_LAN_43", "cale: ataşamente element");
define("CONTENT_ADMIN_OPT_LAN_44", "definiţi calea către ataşamentele elementului de conţinut");
define("CONTENT_ADMIN_OPT_LAN_45", "temă");
define("CONTENT_ADMIN_OPT_LAN_46", "definiţi temele de afişare pentru categoria principală");
define("CONTENT_ADMIN_OPT_LAN_47", "alegeţi o temă de afişare pentru această categorie principală de conţinut. Puteţi crea o nouă temă prin adăugarea unui folder în folderul de şabloane pentru conţinut.");
define("CONTENT_ADMIN_OPT_LAN_48", "general");
define("CONTENT_ADMIN_OPT_LAN_49", "contorizare referenţi");
define("CONTENT_ADMIN_OPT_LAN_50", "activare înregistrări pe contorizarea referenţilor");
define("CONTENT_ADMIN_OPT_LAN_51", "dacă e bifată, o listă a referenţilor va fi stocată pentru fiecare element de conţinut, bazată pe adresa unică de IP.");
define("CONTENT_ADMIN_OPT_LAN_52", "iconiţă element gol");
define("CONTENT_ADMIN_OPT_LAN_53", "afişare iconiţă element gol dacă nu există nici o iconiţă aleasă");
define("CONTENT_ADMIN_OPT_LAN_54", "imagine categorie goală");
define("CONTENT_ADMIN_OPT_LAN_55", "afişare imagine categorie goală dacă nu există o iconiţă predefinită");
define("CONTENT_ADMIN_OPT_LAN_56", "breadcrumb");
define("CONTENT_ADMIN_OPT_LAN_57", "define if a breadcrumb will be shown");
define("CONTENT_ADMIN_OPT_LAN_58", "breadcrumb seperator");
define("CONTENT_ADMIN_OPT_LAN_59", "breadcrumb seperator character ( >> or > or - or ...)");
define("CONTENT_ADMIN_OPT_LAN_60", "choose a character to use as the seperator between each level of the breadcrumb");
define("CONTENT_ADMIN_OPT_LAN_61", "breadcrumb rendertype");
define("CONTENT_ADMIN_OPT_LAN_62", "define how to show the breadcrumb menu");
define("CONTENT_ADMIN_OPT_LAN_63", "define how to render the breadcrumb information. you have three options: one to just echo it out at the top of the page, one to render it in a seperate menu and one to incorporate it into the menus that will come below it.");
define("CONTENT_ADMIN_OPT_LAN_64", "echo");
define("CONTENT_ADMIN_OPT_LAN_65", "use a seperate menu");
define("CONTENT_ADMIN_OPT_LAN_66", "combine into one menu");
define("CONTENT_ADMIN_OPT_LAN_67", "search menu");
define("CONTENT_ADMIN_OPT_LAN_68", "should the search menu be shown");
define("CONTENT_ADMIN_OPT_LAN_69", "if checked a navigation and search menu will be shown to search in content or navigate to other overview pages in content as well as an option to order the content items in the recent pages");
define("CONTENT_ADMIN_OPT_LAN_70", "recent pages (recent (content.php?type.X), contents by category (content.php?type.X.cat.Y), contents by author (content.php?type.X.author.Y))");
define("CONTENT_ADMIN_OPT_LAN_71", "choose which sections should be shown when viewing a content item in the recent pages");
define("CONTENT_ADMIN_OPT_LAN_72", "subheading");
define("CONTENT_ADMIN_OPT_LAN_73", "summary");
define("CONTENT_ADMIN_OPT_LAN_74", "date");
define("CONTENT_ADMIN_OPT_LAN_75", "authordetails");
define("CONTENT_ADMIN_OPT_LAN_76", "authoremail");
define("CONTENT_ADMIN_OPT_LAN_77", "rating");
define("CONTENT_ADMIN_OPT_LAN_78", "email/print/pdf icon");
define("CONTENT_ADMIN_OPT_LAN_79", "parent breadcrumb");
define("CONTENT_ADMIN_OPT_LAN_80", "refer (only if logging enabled)");
define("CONTENT_ADMIN_OPT_LAN_81", "subheading characters");
define("CONTENT_ADMIN_OPT_LAN_82", "define amount of characters of the subheading");
define("CONTENT_ADMIN_OPT_LAN_83", "how many characters of the subheading should be shown? leave blank to show the full subheading");
define("CONTENT_ADMIN_OPT_LAN_84", "subheading postfix");
define("CONTENT_ADMIN_OPT_LAN_85", "define a postfix for too long subheadings");
define("CONTENT_ADMIN_OPT_LAN_86", "summary characters");
define("CONTENT_ADMIN_OPT_LAN_87", "define amount of characters of the summary");
define("CONTENT_ADMIN_OPT_LAN_88", "how many characters of the summary should be shown? leave blank to show the full summary");
define("CONTENT_ADMIN_OPT_LAN_89", "summary postfix");
define("CONTENT_ADMIN_OPT_LAN_90", "define a postfix for too long summaries");
define("CONTENT_ADMIN_OPT_LAN_91", "email non-member");
define("CONTENT_ADMIN_OPT_LAN_92", "show email of non-member author");
define("CONTENT_ADMIN_OPT_LAN_93", "define if the email of a non-member author will be shown. only if authoremail is set in the above sections area.");
define("CONTENT_ADMIN_OPT_LAN_94", "next prev");
define("CONTENT_ADMIN_OPT_LAN_95", "show next prev buttons");
define("CONTENT_ADMIN_OPT_LAN_96", "if enabled only a limited number of items is shown on a list page, and you can navigate through a number of pages to shown other content items.");
define("CONTENT_ADMIN_OPT_LAN_97", "items per page");
define("CONTENT_ADMIN_OPT_LAN_98", "how many content items need to be shown on a single page?");
define("CONTENT_ADMIN_OPT_LAN_99", "this will only be used if the next prev limitations is checked");
define("CONTENT_ADMIN_OPT_LAN_100", "override print/email/pdf");
define("CONTENT_ADMIN_OPT_LAN_101", "show print/email/pdf icons to all items");
define("CONTENT_ADMIN_OPT_LAN_102", "if enabled it shows the icons for all contents and parents, regardless of their individual setting");
define("CONTENT_ADMIN_OPT_LAN_103", "override rating system");
define("CONTENT_ADMIN_OPT_LAN_104", "show rating system for all items");
define("CONTENT_ADMIN_OPT_LAN_105", "if enabled it shows the rating system for all contents and parents, regardless of their individual setting");
define("CONTENT_ADMIN_OPT_LAN_106", "page sort/order");
define("CONTENT_ADMIN_OPT_LAN_107", "choose a default sort and order method");
define("CONTENT_ADMIN_OPT_LAN_108", "order by 'order' will use the ordering number you have provided in the Manage Order area");
define("CONTENT_ADMIN_OPT_LAN_109", "heading (ASC)");
define("CONTENT_ADMIN_OPT_LAN_110", "heading (DESC)");
define("CONTENT_ADMIN_OPT_LAN_111", "date (ASC)");
define("CONTENT_ADMIN_OPT_LAN_112", "date (DESC)");
define("CONTENT_ADMIN_OPT_LAN_113", "refer (ASC)");
define("CONTENT_ADMIN_OPT_LAN_114", "refer (DESC)");
define("CONTENT_ADMIN_OPT_LAN_115", "parent (ASC)");
define("CONTENT_ADMIN_OPT_LAN_116", "parent (DESC)");
define("CONTENT_ADMIN_OPT_LAN_117", "order (ASC)");
define("CONTENT_ADMIN_OPT_LAN_118", "order (DESC)");
define("CONTENT_ADMIN_OPT_LAN_119", "content category page (content.php?type.X.cat.Y)");
define("CONTENT_ADMIN_OPT_LAN_120", "parent item");
define("CONTENT_ADMIN_OPT_LAN_121", "should the parent item be shown");
define("CONTENT_ADMIN_OPT_LAN_122", "parent subcategories");
define("CONTENT_ADMIN_OPT_LAN_123", "should parent subcategories be shown");
define("CONTENT_ADMIN_OPT_LAN_124", "if enabled all underlying subcategories are displayed with the parent category. if disabled only the parent item is shown");
define("CONTENT_ADMIN_OPT_LAN_125", "parent subcategory item");
define("CONTENT_ADMIN_OPT_LAN_126", "should items of parent subcategories be shown");
define("CONTENT_ADMIN_OPT_LAN_127", "if enabled all items from the selected category and items from underlying categories are shown. if disabled only items from the selected category are shown");
define("CONTENT_ADMIN_OPT_LAN_128", "order parent-child");
define("CONTENT_ADMIN_OPT_LAN_129", "define order of parent and child items");
define("CONTENT_ADMIN_OPT_LAN_130", "choose in what order should the parent and child items need to be shown");
define("CONTENT_ADMIN_OPT_LAN_131", "parent above children");
define("CONTENT_ADMIN_OPT_LAN_132", "children above parent");
define("CONTENT_ADMIN_OPT_LAN_133", "rendertype menus");
define("CONTENT_ADMIN_OPT_LAN_134", "choose a render method of all the menus");
define("CONTENT_ADMIN_OPT_LAN_135", "you can render the parent, sub and child items each in their own menu, or you can combine them together into one single menu");
define("CONTENT_ADMIN_OPT_LAN_136", "each in seperate menus");
define("CONTENT_ADMIN_OPT_LAN_137", "combine into one menu");
define("CONTENT_ADMIN_OPT_LAN_138", "content page (content.php?type.X.content.Y)");
define("CONTENT_ADMIN_OPT_LAN_139", "choose which sections should be shown when viewing a content item");
define("CONTENT_ADMIN_OPT_LAN_140", "menu properties");
define("CONTENT_ADMIN_OPT_LAN_141", "caption");
define("CONTENT_ADMIN_OPT_LAN_142", "define the caption of the menu");
define("CONTENT_ADMIN_OPT_LAN_143", "search");
define("CONTENT_ADMIN_OPT_LAN_144", "does the search menu need to be shown");
define("CONTENT_ADMIN_OPT_LAN_145", "sort and order");
define("CONTENT_ADMIN_OPT_LAN_146", "does a selection to use sorting options need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_147", "links to pages");
define("CONTENT_ADMIN_OPT_LAN_148", "link : all categories");
define("CONTENT_ADMIN_OPT_LAN_149", "does a link to the 'all categories' page need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_150", "link : all authors");
define("CONTENT_ADMIN_OPT_LAN_151", "does a link to the 'all authors' page need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_152", "link : top rated");
define("CONTENT_ADMIN_OPT_LAN_153", "does a link to the 'top rated items' page need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_154", "link : recent items");
define("CONTENT_ADMIN_OPT_LAN_155", "does a link to the 'recent content items' page need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_156", "link : submit item");
define("CONTENT_ADMIN_OPT_LAN_157", "does a link to the 'submit content item' page need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_158", "icon : links");
define("CONTENT_ADMIN_OPT_LAN_159", "define the icon to display");
define("CONTENT_ADMIN_OPT_LAN_160", "none (), bullet (), middot (&middot;), white bullet (º), arrow (&raquo;)");
define("CONTENT_ADMIN_OPT_LAN_161", "categories");
define("CONTENT_ADMIN_OPT_LAN_162", "subcategories");
define("CONTENT_ADMIN_OPT_LAN_163", "do the (sub) categories need to be shown if present?");
define("CONTENT_ADMIN_OPT_LAN_164", "amount items");
define("CONTENT_ADMIN_OPT_LAN_165", "does the total number of items in each cat need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_166", "icon : category");
define("CONTENT_ADMIN_OPT_LAN_167", "none (), bullet (), middot (&middot;), white bullet (º), arrow (&raquo;), category_icon()");
define("CONTENT_ADMIN_OPT_LAN_168", "none");
define("CONTENT_ADMIN_OPT_LAN_169", "bullet");
define("CONTENT_ADMIN_OPT_LAN_170", "middot");
define("CONTENT_ADMIN_OPT_LAN_171", "white bullet");
define("CONTENT_ADMIN_OPT_LAN_172", "arrow");
define("CONTENT_ADMIN_OPT_LAN_173", "category icon");
define("CONTENT_ADMIN_OPT_LAN_174", "recent list");
define("CONTENT_ADMIN_OPT_LAN_175", "recent items");
define("CONTENT_ADMIN_OPT_LAN_176", "does a list of recent additions of content items need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_177", "caption : recent list");
define("CONTENT_ADMIN_OPT_LAN_178", "define the caption of the recent list");
define("CONTENT_ADMIN_OPT_LAN_179", "amount recent items");
define("CONTENT_ADMIN_OPT_LAN_180", "how many recent items should be shown");
define("CONTENT_ADMIN_OPT_LAN_181", "date");
define("CONTENT_ADMIN_OPT_LAN_182", "does the date need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_183", "author");
define("CONTENT_ADMIN_OPT_LAN_184", "does the author need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_185", "subheading");
define("CONTENT_ADMIN_OPT_LAN_186", "does the subheading need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_187", "subheading : characters");
define("CONTENT_ADMIN_OPT_LAN_188", "how many characters of the subheading should be shown?");
define("CONTENT_ADMIN_OPT_LAN_189", "leave blank to show the full subheading");
define("CONTENT_ADMIN_OPT_LAN_190", "subheading : postfix");
define("CONTENT_ADMIN_OPT_LAN_191", "define a postfix for too long subheadings");
define("CONTENT_ADMIN_OPT_LAN_192", "icon : recent items");
define("CONTENT_ADMIN_OPT_LAN_193", "none (), bullet (), middot (·), white bullet (º), arrow (»), content_icon()");
define("CONTENT_ADMIN_OPT_LAN_194", "content icon");
define("CONTENT_ADMIN_OPT_LAN_195", "icon : width");
define("CONTENT_ADMIN_OPT_LAN_196", "define the width of the icon");
define("CONTENT_ADMIN_OPT_LAN_197", "if you have chosen to display the 'content icon', specify the width of the content icon to show. enter only a numeric value of the amount of pixels you want. do not add the 'px' to the number.");
define("CONTENT_ADMIN_OPT_LAN_198", "");
define("CONTENT_ADMIN_OPT_LAN_199", "");

define("CONTENT_ADMIN_OPT_LAN_200", "update options");

define("CONTENT_ADMIN_OPT_LAN_201", "override comment system");
define("CONTENT_ADMIN_OPT_LAN_202", "allow comments on all items");
define("CONTENT_ADMIN_OPT_LAN_203", "if enabled it allows the posting of comments on all content items, regardless of their individual setting");
define("CONTENT_ADMIN_OPT_LAN_204", "edit icon : show icon with link to admin to edit content item");
define("CONTENT_ADMIN_OPT_LAN_205", "layout templates");
define("CONTENT_ADMIN_OPT_LAN_206", "custom data");

define("CONTENT_ADMIN_OPT_LAN_207", "rendertype categories");
define("CONTENT_ADMIN_OPT_LAN_208", "define the way the categories need to be shown");
define("CONTENT_ADMIN_OPT_LAN_209", "you can either place the categories in the select box together with the other links, or show them as normal links");
define("CONTENT_ADMIN_OPT_LAN_210", "selectbox");
define("CONTENT_ADMIN_OPT_LAN_211", "normallinks");
define("CONTENT_ADMIN_OPT_LAN_212", "link : all content items");
define("CONTENT_ADMIN_OPT_LAN_213", "does a link to the 'all content items' page (the archive page) need to be shown?");
define("CONTENT_ADMIN_OPT_LAN_214", "datestyle");
define("CONTENT_ADMIN_OPT_LAN_215", "choose a datestyle to use for the displayed date");
define("CONTENT_ADMIN_OPT_LAN_216", "For more information on date formats see the <a href='http://www.php.net/manual/en/function.strftime.php' rel='external'>strftime function page at php.net</a>");
define("CONTENT_ADMIN_OPT_LAN_217", "archive page");
define("CONTENT_ADMIN_OPT_LAN_218", "");
define("CONTENT_ADMIN_OPT_LAN_219", "");



define("CONTENT_ADMIN_ITEM_LAN_0", "required field(s) left blank");
define("CONTENT_ADMIN_ITEM_LAN_1", "content item created");
define("CONTENT_ADMIN_ITEM_LAN_2", "content item updated");
define("CONTENT_ADMIN_ITEM_LAN_3", "content item deleted");
define("CONTENT_ADMIN_ITEM_LAN_4", "no content items yet");
define("CONTENT_ADMIN_ITEM_LAN_5", "existing content items");
define("CONTENT_ADMIN_ITEM_LAN_6", "first letters");
define("CONTENT_ADMIN_ITEM_LAN_7", "please select a letter above.");
define("CONTENT_ADMIN_ITEM_LAN_8", "id");
define("CONTENT_ADMIN_ITEM_LAN_9", "icon");
define("CONTENT_ADMIN_ITEM_LAN_10", "author");
define("CONTENT_ADMIN_ITEM_LAN_11", "heading");
define("CONTENT_ADMIN_ITEM_LAN_12", "options");
define("CONTENT_ADMIN_ITEM_LAN_13", "choose parent category");
define("CONTENT_ADMIN_ITEM_LAN_14", "name");
define("CONTENT_ADMIN_ITEM_LAN_15", "email");
define("CONTENT_ADMIN_ITEM_LAN_16", "subheading");
define("CONTENT_ADMIN_ITEM_LAN_17", "summary");
define("CONTENT_ADMIN_ITEM_LAN_18", "text");
define("CONTENT_ADMIN_ITEM_LAN_19", "upload icon");
define("CONTENT_ADMIN_ITEM_LAN_20", "icon");
define("CONTENT_ADMIN_ITEM_LAN_21", "This option is disabled as file uploading is not enabled on your server");
define("CONTENT_ADMIN_ITEM_LAN_22", "The");
define("CONTENT_ADMIN_ITEM_LAN_23", "folder is not writable, you need to CHMOD 777 the folder before uploading");
define("CONTENT_ADMIN_ITEM_LAN_24", "attachments");
define("CONTENT_ADMIN_ITEM_LAN_25", "upload new icon");
define("CONTENT_ADMIN_ITEM_LAN_26", "remove");
define("CONTENT_ADMIN_ITEM_LAN_27", "present content file");
define("CONTENT_ADMIN_ITEM_LAN_28", "upload new file");
define("CONTENT_ADMIN_ITEM_LAN_29", "no file yet");
define("CONTENT_ADMIN_ITEM_LAN_30", "content file");
define("CONTENT_ADMIN_ITEM_LAN_31", "images");
define("CONTENT_ADMIN_ITEM_LAN_32", "present content image");
define("CONTENT_ADMIN_ITEM_LAN_33", "upload new image");
define("CONTENT_ADMIN_ITEM_LAN_34", "content image");
define("CONTENT_ADMIN_ITEM_LAN_35", "set preferences for this content item");
define("CONTENT_ADMIN_ITEM_LAN_36", "comments");
define("CONTENT_ADMIN_ITEM_LAN_37", "rating");
define("CONTENT_ADMIN_ITEM_LAN_38", "print email/icons");
define("CONTENT_ADMIN_ITEM_LAN_39", "visibility");
define("CONTENT_ADMIN_ITEM_LAN_40", "score");
define("CONTENT_ADMIN_ITEM_LAN_41", "select a score ...");
define("CONTENT_ADMIN_ITEM_LAN_42", "tick to update timestamp to current time");
define("CONTENT_ADMIN_ITEM_LAN_43", "post user submitted content item");
define("CONTENT_ADMIN_ITEM_LAN_44", "create content item");
define("CONTENT_ADMIN_ITEM_LAN_45", "update content item");
define("CONTENT_ADMIN_ITEM_LAN_46", "preview");
define("CONTENT_ADMIN_ITEM_LAN_47", "preview again");
define("CONTENT_ADMIN_ITEM_LAN_48", "main parent");
define("CONTENT_ADMIN_ITEM_LAN_49", "submitted content items");
define("CONTENT_ADMIN_ITEM_LAN_50", "no submitted content items");
define("CONTENT_ADMIN_ITEM_LAN_51", "author details");
define("CONTENT_ADMIN_ITEM_LAN_52", "submit content item");
define("CONTENT_ADMIN_ITEM_LAN_53", "meta keywords");
define("CONTENT_ADMIN_ITEM_LAN_54", "additional data");
define("CONTENT_ADMIN_ITEM_LAN_55", "Go back to the <a href='".e_SELF."'>main contentmanager page</a> to manage more of your personal content<br />or<br />Go to the <a href='".e_PLUGIN."content/content.php'>content main page</a> to view content items.");
define("CONTENT_ADMIN_ITEM_LAN_56", "personal content manager");
define("CONTENT_ADMIN_ITEM_LAN_57", "category");
define("CONTENT_ADMIN_ITEM_LAN_58", "items");
define("CONTENT_ADMIN_ITEM_LAN_59", "move");
define("CONTENT_ADMIN_ITEM_LAN_60", "order");
define("CONTENT_ADMIN_ITEM_LAN_61", "update order");
define("CONTENT_ADMIN_ITEM_LAN_62", "order categories");
define("CONTENT_ADMIN_ITEM_LAN_63", "inc");
define("CONTENT_ADMIN_ITEM_LAN_64", "dec");
define("CONTENT_ADMIN_ITEM_LAN_65", "order content items");
define("CONTENT_ADMIN_ITEM_LAN_66", "Below you see the distinct letters of the content heading for all items in this category.<br />By clicking on one of the letters you will see a list of all items starting with the selected letter. You can also choose the ALL button to display all items in this category.");
define("CONTENT_ADMIN_ITEM_LAN_67", "Below you see the content items listed for the selected category or narrowed down with a selected letter.<br />You can edit or delete an item by clicking the appropriate buttons on the right.");
define("CONTENT_ADMIN_ITEM_LAN_68", "Below you have the ability to add custom data for this content item. Each custom data needs to have both a key and a value data present. You can specify the key in the left field and the corresponding value in the right field.<br />(for instance, key='photography' and value='all photos are made by me'.");
define("CONTENT_ADMIN_ITEM_LAN_69", "Here you can upload icons, attachments and/or images to go with the content item. The allowed filetypes are : ");
define("CONTENT_ADMIN_ITEM_LAN_70", "In the next box you can specify specific meta keywords to go with this content item. These meta keywords are rendered in the header of the page. Seperate each word with commas, and no spaces are allowed !");
define("CONTENT_ADMIN_ITEM_LAN_71", "leave if item written by you");
define("CONTENT_ADMIN_ITEM_LAN_72", "define author details");
define("CONTENT_ADMIN_ITEM_LAN_73", "define a start date for this item (leave if none needed)");
define("CONTENT_ADMIN_ITEM_LAN_74", "define an end date for this item (leave if none needed)");
define("CONTENT_ADMIN_ITEM_LAN_75", "upload and assign an icon to go with this item");
define("CONTENT_ADMIN_ITEM_LAN_76", "upload and assign attachments to go with this item");
define("CONTENT_ADMIN_ITEM_LAN_77", "upload and assign images to go with this item");
define("CONTENT_ADMIN_ITEM_LAN_78", "define if comments should be allowed");
define("CONTENT_ADMIN_ITEM_LAN_79", "define if rating should be allowed");
define("CONTENT_ADMIN_ITEM_LAN_80", "define if print/email icons should be shown");
define("CONTENT_ADMIN_ITEM_LAN_81", "choose which users see this item");
define("CONTENT_ADMIN_ITEM_LAN_82", "define a score");
define("CONTENT_ADMIN_ITEM_LAN_83", "define meta keywords");
define("CONTENT_ADMIN_ITEM_LAN_84", "define custom data fields (key + value)");
define("CONTENT_ADMIN_ITEM_LAN_85", "enabled");
define("CONTENT_ADMIN_ITEM_LAN_86", "disabled");
define("CONTENT_ADMIN_ITEM_LAN_87", "choose an icon for this item");
define("CONTENT_ADMIN_ITEM_LAN_88", "to create an item in the earlier selected main category");
define("CONTENT_ADMIN_ITEM_LAN_89", "to edit an item in the earlier selected main category");
define("CONTENT_ADMIN_ITEM_LAN_90", "click here");
define("CONTENT_ADMIN_ITEM_LAN_91", "to re-edit the same item");
define("CONTENT_ADMIN_ITEM_LAN_92", "template");
define("CONTENT_ADMIN_ITEM_LAN_93", "choose a layout template");
define("CONTENT_ADMIN_ITEM_LAN_94", "select a layout template");
define("CONTENT_ADMIN_ITEM_LAN_95", "");

define("CONTENT_ADMIN_ORDER_LAN_0", "order is increased");
define("CONTENT_ADMIN_ORDER_LAN_1", "order is decreased");
define("CONTENT_ADMIN_ORDER_LAN_2", "new order for content items is saved");

define("CONTENT_ADMIN_MAIN_LAN_0", "existing content categories");
define("CONTENT_ADMIN_MAIN_LAN_1", "no content categories yet");
define("CONTENT_ADMIN_MAIN_LAN_2", "main content categories");
define("CONTENT_ADMIN_MAIN_LAN_3", "content item deleted");
define("CONTENT_ADMIN_MAIN_LAN_4", "parent text");
define("CONTENT_ADMIN_MAIN_LAN_5", "parent icon");
define("CONTENT_ADMIN_MAIN_LAN_6", "");
define("CONTENT_ADMIN_MAIN_LAN_7", "Welcome to the Content Management System !");
define("CONTENT_ADMIN_MAIN_LAN_8", "This information is shown because the table of the Content Management Plugin contains no records.");
define("CONTENT_ADMIN_MAIN_LAN_9", "Please read the following information carefully and choose what you want to do.");
define("CONTENT_ADMIN_MAIN_LAN_10", "You can manage content items on this page. First decide the category you would like to manage content for. Select a category in the select box to start managing content for that category.");
define("CONTENT_ADMIN_MAIN_LAN_11", "Since the old content table contains records, you can choose one of the following three options:");
define("CONTENT_ADMIN_MAIN_LAN_12", "");
define("CONTENT_ADMIN_MAIN_LAN_13", "You can create new content items on this page. First decide the category you would like to manage content for. Click on the button of the main parents listed below to create new content in that main category.");
define("CONTENT_ADMIN_MAIN_LAN_14", "You can set the order for content items on this page. Click on the button of the main parents listed below to start ordering content items or categories for the selected main category.");
define("CONTENT_ADMIN_MAIN_LAN_15", "You can manage categories on this page. Choose the main category from the buttons listed below to show an overview of alle categories and subcategories within that main category.");
define("CONTENT_ADMIN_MAIN_LAN_16", "You can create new categories on this page. By default the creation form for a new main category is shown. If you would like to create a subcategory for an existing main category, please click on one of the buttons listed below to show the creation form for a subcategory within the selected main category.");
define("CONTENT_ADMIN_MAIN_LAN_17", "please create a new category on the <a href='".e_SELF."?type.0.cat.create'>Create New Category</a> page");

define("CONTENT_ADMIN_MAIN_LAN_18", "Convert records");
define("CONTENT_ADMIN_MAIN_LAN_19", "
The first thing you need to do is create a backup of your existing content table as well as your comments and rate table.<br />
Use a program to backup your content table, like phpmyadmin.<br />
After you have created a backup of your old content table, you can start converting the records to the new Content Management Plugin.<br />
After you have converted your old content, you should no longer see this information, and be able to manage your existing content.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_20", "Start with an empty content table");
define("CONTENT_ADMIN_MAIN_LAN_21", "
If you no longer need the records from your old content table,<br />
and just want to start with a fresh new Content Management Plugin table,<br />
and you do not want to create a default set of categories,<br />
you can start by creating a new category.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_22", "Create a default set of categories");
define("CONTENT_ADMIN_MAIN_LAN_23", "
If you want to start with a fresh install, you can first create a default set of content categories.<br />
With this default set three main parent categories will be created, namely Content, Review and Article.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_24", "This is a fresh install / The old content table does not contain records");
define("CONTENT_ADMIN_MAIN_LAN_25", "
Since the old existing content table does not contain any records, you can now start managing new content.<br />
By clicking the next button, you will automatically create a default set of categories, namely Content, Review and Article.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_26", "");
define("CONTENT_ADMIN_MAIN_LAN_27", "");
define("CONTENT_ADMIN_MAIN_LAN_28", "");
define("CONTENT_ADMIN_MAIN_LAN_29", "");



define("CONTENT_ADMIN_MENU_LAN_0", "Manage Existing Content");
define("CONTENT_ADMIN_MENU_LAN_1", "Create New Content");
define("CONTENT_ADMIN_MENU_LAN_2", "Manage Existing Categories");
define("CONTENT_ADMIN_MENU_LAN_3", "Create New Category");
define("CONTENT_ADMIN_MENU_LAN_4", "Submitted Content Items");
define("CONTENT_ADMIN_MENU_LAN_5", "Category");
define("CONTENT_ADMIN_MENU_LAN_6", "Options");
define("CONTENT_ADMIN_MENU_LAN_7", "Create");
define("CONTENT_ADMIN_MENU_LAN_8", "Submit");
define("CONTENT_ADMIN_MENU_LAN_9", "Path and Theme");
define("CONTENT_ADMIN_MENU_LAN_10", "General");
define("CONTENT_ADMIN_MENU_LAN_11", "Recent pages");
define("CONTENT_ADMIN_MENU_LAN_12", "Category pages");
define("CONTENT_ADMIN_MENU_LAN_13", "Content pages");
define("CONTENT_ADMIN_MENU_LAN_14", "Menu");
define("CONTENT_ADMIN_MENU_LAN_15", "Manage Order");
define("CONTENT_ADMIN_MENU_LAN_16", "Archive Page");

define("CONTENT_ADMIN_JS_LAN_0", "Are you sure you want to delete this category?");
define("CONTENT_ADMIN_JS_LAN_1", "Are you sure you want to delete this content?");
define("CONTENT_ADMIN_JS_LAN_2", "are you sure you want to remove the current image ?");
define("CONTENT_ADMIN_JS_LAN_3", "are you sure you want to remove the current file ?");
define("CONTENT_ADMIN_JS_LAN_4", "image");
define("CONTENT_ADMIN_JS_LAN_5", "file");
define("CONTENT_ADMIN_JS_LAN_6", "ID");
define("CONTENT_ADMIN_JS_LAN_7", "are you sure you want to remove the current icon ?");
define("CONTENT_ADMIN_JS_LAN_8", "icon");
define("CONTENT_ADMIN_JS_LAN_9", "NOTICE :\\nonly empty categories can be deleted.\\na category is empty if it does NOT contain any subcategories and\\nif it does NOT contain any content items!");
define("CONTENT_ADMIN_JS_LAN_10", "Are you sure you want to delete this submitted content before posting it?");

define("CONTENT_ADMIN_DATE_LAN_0", "January");
define("CONTENT_ADMIN_DATE_LAN_1", "February");
define("CONTENT_ADMIN_DATE_LAN_2", "March");
define("CONTENT_ADMIN_DATE_LAN_3", "April");
define("CONTENT_ADMIN_DATE_LAN_4", "May");
define("CONTENT_ADMIN_DATE_LAN_5", "June");
define("CONTENT_ADMIN_DATE_LAN_6", "July");
define("CONTENT_ADMIN_DATE_LAN_7", "August");
define("CONTENT_ADMIN_DATE_LAN_8", "September");
define("CONTENT_ADMIN_DATE_LAN_9", "October");
define("CONTENT_ADMIN_DATE_LAN_10", "November");
define("CONTENT_ADMIN_DATE_LAN_11", "December");
define("CONTENT_ADMIN_DATE_LAN_12", "day");
define("CONTENT_ADMIN_DATE_LAN_13", "month");
define("CONTENT_ADMIN_DATE_LAN_14", "year");
define("CONTENT_ADMIN_DATE_LAN_15", "start date");
define("CONTENT_ADMIN_DATE_LAN_16", "end date");
define("CONTENT_ADMIN_DATE_LAN_17", "You can specify a start date for this content item. If you use a date in the future the content item will be visible from that point onward. If you do not need a specific starting date, you can just leave these fields as they are.");
define("CONTENT_ADMIN_DATE_LAN_18", "you can specify an end date for this content item. With the end date you can specify until which point in time the content item needs to be visible. If you do not need an end time for this content item, you can just leave the fields as they are.");

define("CONTENT_LAN_0", "Content");
define("CONTENT_LAN_1", "Recent List");
define("CONTENT_LAN_2", "Category List");
define("CONTENT_LAN_3", "Category");
define("CONTENT_LAN_4", "Author List");
define("CONTENT_LAN_5", "Author");
define("CONTENT_LAN_6", "all categories");
define("CONTENT_LAN_7", "all authors");
define("CONTENT_LAN_8", "top rated items");
define("CONTENT_LAN_9", "sort by ...");
define("CONTENT_LAN_10", "heading (ASC)");
define("CONTENT_LAN_11", "heading (DESC)");
define("CONTENT_LAN_12", "date (ASC)");
define("CONTENT_LAN_13", "date (DESC)");
define("CONTENT_LAN_14", "refer (ASC)");
define("CONTENT_LAN_15", "refer (DESC)");
define("CONTENT_LAN_16", "parent (ASC)");
define("CONTENT_LAN_17", "parent (DESC)");
define("CONTENT_LAN_18", "search by keyword");
define("CONTENT_LAN_19", "search");
define("CONTENT_LAN_20", "content search results");
define("CONTENT_LAN_21", "no content types yet.");
define("CONTENT_LAN_22", "content types");
define("CONTENT_LAN_23", "content recent list");
define("CONTENT_LAN_24", "breadcrumb");
define("CONTENT_LAN_25", "content categories");
define("CONTENT_LAN_26", "parent");
define("CONTENT_LAN_27", "subcategories");
define("CONTENT_LAN_28", "parent subcategories");
define("CONTENT_LAN_29", "unknown");
define("CONTENT_LAN_30", "content item");
define("CONTENT_LAN_31", "content items");
define("CONTENT_LAN_32", "content author list");
define("CONTENT_LAN_33", "Go To Page");
define("CONTENT_LAN_34", "content");
define("CONTENT_LAN_35", "comments");
define("CONTENT_LAN_36", "moderate comments");
define("CONTENT_LAN_37", "no content items rated yet");
define("CONTENT_LAN_38", "top rated content items");
define("CONTENT_LAN_39", "author list");
define("CONTENT_LAN_40", "author user details");
define("CONTENT_LAN_41", "download attached");
define("CONTENT_LAN_42", "file");
define("CONTENT_LAN_43", "files");
define("CONTENT_LAN_44", "hits:");
define("CONTENT_LAN_45", "author awarded score:");
define("CONTENT_LAN_46", "article index");
define("CONTENT_LAN_47", "author");
define("CONTENT_LAN_48", "content items");
define("CONTENT_LAN_49", "last content item");
define("CONTENT_LAN_50", "date");
define("CONTENT_LAN_51", "Type List");
define("CONTENT_LAN_52", "no valid authors found");
define("CONTENT_LAN_53", "item");
define("CONTENT_LAN_54", "items");
define("CONTENT_LAN_55", "last item on");
define("CONTENT_LAN_56", "show overview of");
define("CONTENT_LAN_57", "comments:");
define("CONTENT_LAN_58", "home");
define("CONTENT_LAN_59", "content");
define("CONTENT_LAN_60", "recent");
define("CONTENT_LAN_61", "view recent items");
define("CONTENT_LAN_62", "view all categories");
define("CONTENT_LAN_63", "view all authors");
define("CONTENT_LAN_64", "view top rated items");
define("CONTENT_LAN_65", "submit content");
define("CONTENT_LAN_66", "click here to submit content, you can choose the category on the submit page.");
define("CONTENT_LAN_67", "personal content manager");
define("CONTENT_LAN_68", "click here to manage your personal content.");
define("CONTENT_LAN_69", "email the");
define("CONTENT_LAN_70", "print the");
define("CONTENT_LAN_71", "content item");
define("CONTENT_LAN_72", "category item");
define("CONTENT_LAN_73", "order (ASC)");
define("CONTENT_LAN_74", "order (DESC)");
define("CONTENT_LAN_75", "submit content item");
define("CONTENT_LAN_76", "create pdf file of the");
define("CONTENT_LAN_77", "content search");
define("CONTENT_LAN_78", "untitled page");
define("CONTENT_LAN_79", "page");
define("CONTENT_LAN_80", "recent items : ");
define("CONTENT_LAN_81", "categories");
define("CONTENT_LAN_82", "no items yet in");
define("CONTENT_LAN_83", "item archive");
define("CONTENT_LAN_84", "content archive");
define("CONTENT_LAN_85", "");
define("CONTENT_LAN_86", "");
define("CONTENT_LAN_87", "");
define("CONTENT_LAN_88", "");
define("CONTENT_LAN_89", "");


define("CONTENT_ADMIN_SUBMIT_LAN_0", "no content categories allow user submission at this point");
define("CONTENT_ADMIN_SUBMIT_LAN_1", "content submit types");
define("CONTENT_ADMIN_SUBMIT_LAN_2", "Thank You, your content item has been submitted.");
define("CONTENT_ADMIN_SUBMIT_LAN_3", "Thank you, your content item has been submitted and will be reviewed by a site administrator in due course.");
define("CONTENT_ADMIN_SUBMIT_LAN_4", "required field(s) left blank");
define("CONTENT_ADMIN_SUBMIT_LAN_5", "Go back to the <a href='".e_SELF."'>main submission page</a> to submit more content<br />or<br />Go to the <a href='".e_PLUGIN."content/content.php'>content main page</a> to view content items.");
define("CONTENT_ADMIN_SUBMIT_LAN_6", "Content Type List");
define("CONTENT_ADMIN_SUBMIT_LAN_7", "Content Type Submission");
define("CONTENT_ADMIN_SUBMIT_LAN_8", "submitted content item deleted");
define("CONTENT_ADMIN_SUBMIT_LAN_9", "");
define("CONTENT_ADMIN_SUBMIT_LAN_10", "");
define("CONTENT_ADMIN_SUBMIT_LAN_11", "");
define("CONTENT_ADMIN_SUBMIT_LAN_12", "");
define("CONTENT_ADMIN_SUBMIT_LAN_13", "");
define("CONTENT_ADMIN_SUBMIT_LAN_14", "");
define("CONTENT_ADMIN_SUBMIT_LAN_15", "");
define("CONTENT_ADMIN_SUBMIT_LAN_16", "");
define("CONTENT_ADMIN_SUBMIT_LAN_17", "");
define("CONTENT_ADMIN_SUBMIT_LAN_18", "");
define("CONTENT_ADMIN_SUBMIT_LAN_19", "");


define("CONTENT_ADMIN_CONVERSION_LAN_0", "content");
define("CONTENT_ADMIN_CONVERSION_LAN_1", "review");
define("CONTENT_ADMIN_CONVERSION_LAN_2", "article");
define("CONTENT_ADMIN_CONVERSION_LAN_3", "category");
define("CONTENT_ADMIN_CONVERSION_LAN_4", "categories");
define("CONTENT_ADMIN_CONVERSION_LAN_5", "page");
define("CONTENT_ADMIN_CONVERSION_LAN_6", "pages");
define("CONTENT_ADMIN_CONVERSION_LAN_7", "main parent inserted");
define("CONTENT_ADMIN_CONVERSION_LAN_8", "main parent preferences inserted");
define("CONTENT_ADMIN_CONVERSION_LAN_9", "no");
define("CONTENT_ADMIN_CONVERSION_LAN_10", "main parent needed");
define("CONTENT_ADMIN_CONVERSION_LAN_11", "CONVERSION ANALYSIS");
define("CONTENT_ADMIN_CONVERSION_LAN_12", "total rows to convert");
define("CONTENT_ADMIN_CONVERSION_LAN_13", "total converted rows");
define("CONTENT_ADMIN_CONVERSION_LAN_14", "total warnings");
define("CONTENT_ADMIN_CONVERSION_LAN_15", "total failures");
define("CONTENT_ADMIN_CONVERSION_LAN_16", "OLD CONTENT TABLE : ANALYSIS");
define("CONTENT_ADMIN_CONVERSION_LAN_17", "total rows");
define("CONTENT_ADMIN_CONVERSION_LAN_18", "unknown rows");
define("CONTENT_ADMIN_CONVERSION_LAN_19", "all rows are familiar");
define("CONTENT_ADMIN_CONVERSION_LAN_20", "CONTENT MAIN PARENT");
define("CONTENT_ADMIN_CONVERSION_LAN_21", "REVIEW MAIN PARENT");
define("CONTENT_ADMIN_CONVERSION_LAN_22", "ARTICLE MAIN PARENT");
define("CONTENT_ADMIN_CONVERSION_LAN_23", "insertion failed");
define("CONTENT_ADMIN_CONVERSION_LAN_24", "NO CONTENT PAGES PRESENT");
define("CONTENT_ADMIN_CONVERSION_LAN_25", "CONTENT PAGES PRESENT");
define("CONTENT_ADMIN_CONVERSION_LAN_26", "inserted");
define("CONTENT_ADMIN_CONVERSION_LAN_27", "conversion analysis");
define("CONTENT_ADMIN_CONVERSION_LAN_28", "total old rows");
define("CONTENT_ADMIN_CONVERSION_LAN_29", "total new rows");
define("CONTENT_ADMIN_CONVERSION_LAN_30", "failed");
define("CONTENT_ADMIN_CONVERSION_LAN_31", "warnings");
define("CONTENT_ADMIN_CONVERSION_LAN_32", "old category does not exist: items added to higher category");
define("CONTENT_ADMIN_CONVERSION_LAN_33", "new category does not exist: items added to higher category");
define("CONTENT_ADMIN_CONVERSION_LAN_34", "no");
define("CONTENT_ADMIN_CONVERSION_LAN_35", "category pages present");
define("CONTENT_ADMIN_CONVERSION_LAN_36", "pages and/or submitted pages present");
define("CONTENT_ADMIN_CONVERSION_LAN_37", "conversion of categories");
define("CONTENT_ADMIN_CONVERSION_LAN_38", "valid inserts");
define("CONTENT_ADMIN_CONVERSION_LAN_39", "failed inserts");
define("CONTENT_ADMIN_CONVERSION_LAN_40", "warning");
define("CONTENT_ADMIN_CONVERSION_LAN_41", "warning");
define("CONTENT_ADMIN_CONVERSION_LAN_42", "conversion results of the old content table to the new content plugin table");
define("CONTENT_ADMIN_CONVERSION_LAN_43", "press the button to convert the old content table");
define("CONTENT_ADMIN_CONVERSION_LAN_44", "the new content table already contains data !<br />are you sure you want to convert the old content table to the new content table ?<br /><br />if you still would like to convert the table, the old content data will be added to the already existing new content table, but no garantee can be made to assure that all items will be added to already existing new categories in a correct manner !");
define("CONTENT_ADMIN_CONVERSION_LAN_45", "insertion failed: main parent not inserted");
define("CONTENT_ADMIN_CONVERSION_LAN_46", "Start managing your content by going to the <a href='".e_PLUGIN."content/admin_content_config.php'>Content Manager Plugin Frontpage</a> !");
define("CONTENT_ADMIN_CONVERSION_LAN_47", "conversion completed");
define("CONTENT_ADMIN_CONVERSION_LAN_48", "click here for details");
define("CONTENT_ADMIN_CONVERSION_LAN_49", "conversion of pages");
define("CONTENT_ADMIN_CONVERSION_LAN_50", "conversion of main parents");
define("CONTENT_ADMIN_CONVERSION_LAN_51", "unknown rows");
define("CONTENT_ADMIN_CONVERSION_LAN_52", "default set of main parent categories created");
define("CONTENT_ADMIN_CONVERSION_LAN_53", "a main parent by this name already exists");
define("CONTENT_ADMIN_CONVERSION_LAN_54", "create a default set of parent categories (content, review and article)");
define("CONTENT_ADMIN_CONVERSION_LAN_55", "content management plugin : conversion options");
define("CONTENT_ADMIN_CONVERSION_LAN_56", "click the button to go to the Content Create New Category page.");
define("CONTENT_ADMIN_CONVERSION_LAN_57", "choose parent");


?>