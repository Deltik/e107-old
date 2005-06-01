<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Dutch/lan_content.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-06-01 04:44:53 $
|     $Author: mijnheer $
+----------------------------------------------------------------------------+
*/

define("CONTENT_ICON_LAN_0", "bewerk");
define("CONTENT_ICON_LAN_1", "verwijder");
define("CONTENT_ICON_LAN_2", "opties");
define("CONTENT_ICON_LAN_3", "ledeninfo");
define("CONTENT_ICON_LAN_4", "download bijlage");
define("CONTENT_ICON_LAN_5", "nieuw");
define("CONTENT_ICON_LAN_6", "plaats content");
define("CONTENT_ICON_LAN_7", "auteurslijst");
define("CONTENT_ICON_LAN_8", "waarschuwing");
define("CONTENT_ICON_LAN_9", "ok");
define("CONTENT_ICON_LAN_10", "fout");
define("CONTENT_ICON_LAN_11", "volgorde onderdelen per categorie");
define("CONTENT_ICON_LAN_12", "volgorde onderdelen in hoofdonderwerp");
define("CONTENT_ICON_LAN_13", "persoonlijke beheerder");
define("CONTENT_ICON_LAN_14", "persoonlijke contentbeheerder");

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

define("LAN_38", "stem");
define("LAN_39", "stemmen");
define("LAN_40", "hoe beoordeel je dit onderwerp?");
define("LAN_41", "bedankt voor je stem");
define("LAN_65", "niet beoordeeld");

define("CONTENT_ADMIN_CAT_LAN_0", "creëren onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_1", "bewerken onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_2", "koptekst onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_3", "onderkop onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_4", "tekst onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_5", "pictogram onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_6", "plaatsen");
define("CONTENT_ADMIN_CAT_LAN_7", "bijwerken");
define("CONTENT_ADMIN_CAT_LAN_8", "bekijk pictogrammen");
define("CONTENT_ADMIN_CAT_LAN_9", "nog geen onderwerpcategorieën");
define("CONTENT_ADMIN_CAT_LAN_10", "onderwerpcategorieën");
define("CONTENT_ADMIN_CAT_LAN_11", "onderwerpcategorie aangemaakt");
define("CONTENT_ADMIN_CAT_LAN_12", "onderwerpcategorieën bijgewerkt");
define("CONTENT_ADMIN_CAT_LAN_13", "verplicht(e) veld(en) niet ingevuld");
define("CONTENT_ADMIN_CAT_LAN_14", "toestaan reacties");
define("CONTENT_ADMIN_CAT_LAN_15", "toestaan beoordeling");
define("CONTENT_ADMIN_CAT_LAN_16", "tonen print/e-mail pictogram");
define("CONTENT_ADMIN_CAT_LAN_17", "zichtbaarheid");
define("CONTENT_ADMIN_CAT_LAN_18", "auteur");
define("CONTENT_ADMIN_CAT_LAN_19", "onderwerpcategorie");
define("CONTENT_ADMIN_CAT_LAN_20", "opties");
define("CONTENT_ADMIN_CAT_LAN_21", "schonen formulier");
define("CONTENT_ADMIN_CAT_LAN_22", "opties bijgewerkt");
define("CONTENT_ADMIN_CAT_LAN_23", "onderwerpcategorie verwijderd");
define("CONTENT_ADMIN_CAT_LAN_24", "id");
define("CONTENT_ADMIN_CAT_LAN_25", "pictogram");
define("CONTENT_ADMIN_CAT_LAN_26", "nieuwe hoofdcategorie");
define("CONTENT_ADMIN_CAT_LAN_27", "categorie");
define("CONTENT_ADMIN_CAT_LAN_28", "Toewijzen beheerders aan de persoonlijke beheerder van deze categorie");
define("CONTENT_ADMIN_CAT_LAN_29", "Beheerders - klik om te verplaatsen ... ");
define("CONTENT_ADMIN_CAT_LAN_30", "Persoonlijke beheerders van deze categorie ...");
define("CONTENT_ADMIN_CAT_LAN_31", "verwijder");
define("CONTENT_ADMIN_CAT_LAN_32", "schonen klasse");
define("CONTENT_ADMIN_CAT_LAN_33", "toewijzen beheerders aan categorie");
define("CONTENT_ADMIN_CAT_LAN_34", "beheerders succesvol toegewezen aan de categorie");
define("CONTENT_ADMIN_CAT_LAN_35", "onderwerp subcategorie verwijderd");
define("CONTENT_ADMIN_CAT_LAN_36", "categorie controle: er zijn nog steeds subcategorieën aanwezig, de categorie wordt NIET verwijderd. Verwijder eerst alle subcategorieën en probeer het daarna nogmaals.");
define("CONTENT_ADMIN_CAT_LAN_37", "onderwerp controle: er zijn nog steeds onderwerpen aanwezig, de categorie wordt NIET verwijderd. Verwijder eerst alle onderwerpen en probeer het daarna nogmaals.");
define("CONTENT_ADMIN_CAT_LAN_38", "onderwerp controle: niets gevonden");
define("CONTENT_ADMIN_CAT_LAN_39", "categorie controle: geen subcategorieën gevonden");
define("CONTENT_ADMIN_CAT_LAN_40", "Hieronder staat een overzicht van de hoofdcategorie en alle eventuele subcategorieën.<br />");

define("CONTENT_ADMIN_CAT_LAN_41", "De persoonlijke beheerder van contentcategorieën staat je toe om bepaalde andere beheerders aan een categorie toe te voegen. Met dit recht kunnen deze beheerders hun eigen en onderwerpen in deze specifieke categorie beheren, zonder de volledige contentbeheer plugin te hoeven gebruiken. In de normale content pagina buiten de beheerfunctie zien ze alleen het personalmanager pictogram dat ze naar de personal manager pagina leidt.");
define("CONTENT_ADMIN_CAT_LAN_42", "om een categorie van de eerder geselecteerde hoofdcategorie te bewerken");

define("CONTENT_ADMIN_CAT_LAN_43", "klik hier");
define("CONTENT_ADMIN_CAT_LAN_44", "om een andere categorie in de eerder geselecteerde hoofdcategorie toe te voegen");
define("CONTENT_ADMIN_CAT_LAN_45", "bepalen of reacties zijn toegestaan");
define("CONTENT_ADMIN_CAT_LAN_46", "bepalen of beoordeling is toegestaan");
define("CONTENT_ADMIN_CAT_LAN_47", "bepalen of afdruk/e-mail pictogrammen worden getoond");
define("CONTENT_ADMIN_CAT_LAN_48", "selecteer welke gebruikers dit onderwerp zien");
define("CONTENT_ADMIN_CAT_LAN_49", "kies een pictogram voor deze categorie");
//define("CONTENT_ADMIN_CAT_LAN_50", "content menu gecreëerd<br /><br />");
define("CONTENT_ADMIN_CAT_LAN_50", "alleen als je een nieuwe Hoofd oudercategorie hebt aangemaakt is een menubestand aangemaakt.<br />
Dit menubestand is opgeslagen in de /menus directory.<br />
Om dit menu te gebruiken moet je het eerst activeren in het <a href='".e_ADMIN."menus.php'>menubeheerscherm</a>.<br /><br />");
define("CONTENT_ADMIN_CAT_LAN_51", "fout; menubestand niet gecreëerd");
define("CONTENT_ADMIN_CAT_LAN_52", "kies ALTIJD EERST een categorie voordat je de andere velden invult!");
define("CONTENT_ADMIN_CAT_LAN_53", "hoofd ouder categorie");

define("CONTENT_ADMIN_OPT_LAN_0", "opties");
define("CONTENT_ADMIN_OPT_LAN_1", "beheeropties: aanmaken van onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_2", "secties");
define("CONTENT_ADMIN_OPT_LAN_3", "bepaal welke secties beschikbaar zijn bij het creëren van een contentonderwerp in het beheerscherm");
define("CONTENT_ADMIN_OPT_LAN_4", "pictogram");
define("CONTENT_ADMIN_OPT_LAN_5", "bijlagen");
define("CONTENT_ADMIN_OPT_LAN_6", "afbeeldingen");
define("CONTENT_ADMIN_OPT_LAN_7", "reactie");
define("CONTENT_ADMIN_OPT_LAN_8", "beoordeling");
define("CONTENT_ADMIN_OPT_LAN_9", "score");
define("CONTENT_ADMIN_OPT_LAN_10", "e-mail/afdruk/pdf pictogram");
define("CONTENT_ADMIN_OPT_LAN_11", "zichtbaarheid");
define("CONTENT_ADMIN_OPT_LAN_12", "meta definitie");
define("CONTENT_ADMIN_OPT_LAN_13", "maatwerk gegevenstags");
define("CONTENT_ADMIN_OPT_LAN_14", "bepaal het aantal aanvullende gegevenstags");
define("CONTENT_ADMIN_OPT_LAN_15", "met behulp van maatwerk tags kun je sleutel => waarde paren maken waarin aanvullende gegevens bij een onderwerp kunnen worden vastgelegd. als je bijvoorbeeld toe wilt voegen 'fotografie: door beheerder', wordt ´fotografie´ de sleutel en ´door beheerder´ de waarde.");
define("CONTENT_ADMIN_OPT_LAN_16", "afbeeldingen");
define("CONTENT_ADMIN_OPT_LAN_17", "instellen hoeveel afbeeldingen bij een onderwerp kunnen worden geupload");
define("CONTENT_ADMIN_OPT_LAN_18", "dit geldt alleen als afbeeldingen bij dit onderwerp zijn toegestaan in de bovenstaande secties");
define("CONTENT_ADMIN_OPT_LAN_19", "bijlagen");
define("CONTENT_ADMIN_OPT_LAN_20", "instellen hoeveel bijlagen bij een onderwerp kunnen worden geupload");
define("CONTENT_ADMIN_OPT_LAN_21", "dit geldt alleen als bijlagen bij dit onderwerp zijn toegestaan in de bovenstaande secties");
define("CONTENT_ADMIN_OPT_LAN_22", "aanmelding: opties voor content aanmeldpagina en formulier");
define("CONTENT_ADMIN_OPT_LAN_23", "aanmelden onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_24", "Toestaan dat onderwerpen kunnen worden aangemeld");
define("CONTENT_ADMIN_OPT_LAN_25", "wanneer toegestaan, kunnen bezoekers onderwerpen aanmelden");
define("CONTENT_ADMIN_OPT_LAN_26", "onderwerp aanmeldklasse");
define("CONTENT_ADMIN_OPT_LAN_27", "bepaal welke ledenklasse onderwerpen kunnen aanmelden");
define("CONTENT_ADMIN_OPT_LAN_28", "direct plaatsen");
define("CONTENT_ADMIN_OPT_LAN_29", "toestaan van het direct plaatsen van aangemelden onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_30", "wanneer Direct plaatsen is aangekruist, wordt een aangemeld onderwerp direct toegevoegd aan de database en wordt het onmiddellijk op de site getoond. wanneer dit niet is aangekruist, zal een beheerder het aangemelde onderwerp ter beoordeling te zien krijgen in het beheerscherm.");
define("CONTENT_ADMIN_OPT_LAN_31", "bepaal welke secties beschikbaar zijn voor aanmelding");
define("CONTENT_ADMIN_OPT_LAN_32", "kies in welke onderdelen je bezoekers onderwerpen wilt laten aanmelden");
define("CONTENT_ADMIN_OPT_LAN_33", "paden");
define("CONTENT_ADMIN_OPT_LAN_34", "hier kun je bepalen waar de afbeeldingen zijn of worden opgeslagen. gebruik accolades ( { } ) als aanduiding van de gebruikelijke e107 gerelateerde padnamen (zoals ( {e_PLUGIN} of {e_IMAGE} ). er zijn twee prictogrammen nodig voor onderwerpcategorieën: een kleine en een grote set pictogrammen.");
define("CONTENT_ADMIN_OPT_LAN_35", "pad : cat pictogrammen groot");
define("CONTENT_ADMIN_OPT_LAN_36", "bepaal het pad naar content cat pictogrammen (groot)");
define("CONTENT_ADMIN_OPT_LAN_37", "pad : cat pictogrammen klein");
define("CONTENT_ADMIN_OPT_LAN_38", "bepaal het pad naar content cat pictogrammen (klein)");
define("CONTENT_ADMIN_OPT_LAN_39", "pad : onderwerp pictogrammen");
define("CONTENT_ADMIN_OPT_LAN_40", "bepaal het pad naar onderwerp pictogrammen");
define("CONTENT_ADMIN_OPT_LAN_41", "pad : onderwerpafbeeldingen");
define("CONTENT_ADMIN_OPT_LAN_42", "bepaal het pad naar onderwerp afbeeldingen");
define("CONTENT_ADMIN_OPT_LAN_43", "pad : onderwerp bijlagen");
define("CONTENT_ADMIN_OPT_LAN_44", "bepaal het pad naar onderwerp bijlagen");
define("CONTENT_ADMIN_OPT_LAN_45", "thema");
define("CONTENT_ADMIN_OPT_LAN_46", "bepaal de thema layout voor deze hoofdcategorie");
define("CONTENT_ADMIN_OPT_LAN_47", "kies een layout thema voor deze hoofdcategorie. je kunt een nieuw thema creëren door een map in de content plugin sjabloondirectorie aan te maken.");
define("CONTENT_ADMIN_OPT_LAN_48", "algemeen");
define("CONTENT_ADMIN_OPT_LAN_49", "verwijzingsteller");
define("CONTENT_ADMIN_OPT_LAN_50", "activeren logging van aantal verwijzingen");
define("CONTENT_ADMIN_OPT_LAN_51", "wanneer aangekruist wordt een verwijzingsteller voor elk onderwerp opgeslagen gebaseerd op unieke IP adressen.");
define("CONTENT_ADMIN_OPT_LAN_52", "blanco onderwerp pictogram");
define("CONTENT_ADMIN_OPT_LAN_53", "toon een blanco onderwerppictogram als er geen pictogram is gedefinieerd");
define("CONTENT_ADMIN_OPT_LAN_54", "blanco cat pictogram");
define("CONTENT_ADMIN_OPT_LAN_55", "toon blanco cateporiepictogram als er geen pictogram is gedefinieerd");
define("CONTENT_ADMIN_OPT_LAN_56", "kruimelpad");
define("CONTENT_ADMIN_OPT_LAN_57", "geef op of het kruimelpad moet worden getoond");
define("CONTENT_ADMIN_OPT_LAN_58", "kruimelpad scheiding");
define("CONTENT_ADMIN_OPT_LAN_59", "kruimelpad scheidingsteken ( >> of > of - of ...)");
define("CONTENT_ADMIN_OPT_LAN_60", "kies een teken dat de niveaus binnen het kruimelpad scheidt");
define("CONTENT_ADMIN_OPT_LAN_61", "kruimelpad weergave");
define("CONTENT_ADMIN_OPT_LAN_62", "bepaal hoe het kruimelpadmenu wordt weergegeven");
define("CONTENT_ADMIN_OPT_LAN_63", "bepaal hoe de kruimelpadinformatie wordt weergegeven. er zijn drie mogelijkheden: plaats het gewoon bovenaan de pagina, toon het in een apart menu of toon het in de menu´s die eronder worden getoond.");
define("CONTENT_ADMIN_OPT_LAN_64", "bovenaan");
define("CONTENT_ADMIN_OPT_LAN_65", "gebruik een apart menu");
define("CONTENT_ADMIN_OPT_LAN_66", "combineer in één menu");
define("CONTENT_ADMIN_OPT_LAN_67", "zoekmenu");
define("CONTENT_ADMIN_OPT_LAN_68", "moet het zoekmenu worden getoond");
define("CONTENT_ADMIN_OPT_LAN_69", "wanneer aangekruist wordt een zoek en navigatiemenu getoond om in de content te zoeken of tussen de overige overzichtspagina´s te navigeren en waarin ook de mogelijkheid wordt geboden om de onderwerpen te sorteren in de overzichtspagina's");
define("CONTENT_ADMIN_OPT_LAN_70", "overzichtspagina's (recent (content.php?type.X), content per categorie (content.php?type.X.cat.Y), contents per auteur (content.php?type.X.author.Y))");
define("CONTENT_ADMIN_OPT_LAN_71", "kies welke secties moeten worden getoond bij het bekijken van een onderwerp in de overzichtspagina's");
define("CONTENT_ADMIN_OPT_LAN_72", "onderkop");
define("CONTENT_ADMIN_OPT_LAN_73", "samenvatting");
define("CONTENT_ADMIN_OPT_LAN_74", "datum");
define("CONTENT_ADMIN_OPT_LAN_75", "auteursgegevens");
define("CONTENT_ADMIN_OPT_LAN_76", "auteurs e-mailadres");
define("CONTENT_ADMIN_OPT_LAN_77", "beoordeling");
define("CONTENT_ADMIN_OPT_LAN_78", "e-mail/afdruk/pdf pictogram");
define("CONTENT_ADMIN_OPT_LAN_79", "ouder kruimel");
define("CONTENT_ADMIN_OPT_LAN_80", "verwijzer (alleen als logging is geactiveerd)");
define("CONTENT_ADMIN_OPT_LAN_81", "lengte onderkop");
define("CONTENT_ADMIN_OPT_LAN_82", "bepaal het aantal tekens van de onderkop");
define("CONTENT_ADMIN_OPT_LAN_83", "hoeveel tekens van de onderkop moeten worden getoond? niets invullen om de hele onderkop te tonen");
define("CONTENT_ADMIN_OPT_LAN_84", "onderkop postfix");
define("CONTENT_ADMIN_OPT_LAN_85", "bepaal de postfix voor te lange onderkoppen");
define("CONTENT_ADMIN_OPT_LAN_86", "lengte samenvatting");
define("CONTENT_ADMIN_OPT_LAN_87", "bepaal de lengte in tekens van de samenvatting");
define("CONTENT_ADMIN_OPT_LAN_88", "hoeveel tekens van de samenvatting moeten worden getoond? niets invullen om de hele samenvatting te tonen");
define("CONTENT_ADMIN_OPT_LAN_89", "samenvatting postfix");
define("CONTENT_ADMIN_OPT_LAN_90", "bepaal de postfix voor te lange samenvattingen");
define("CONTENT_ADMIN_OPT_LAN_91", "e-mailadres niet-lid");
define("CONTENT_ADMIN_OPT_LAN_92", "toon het e-maiadres van een auteur/niet-lid");
define("CONTENT_ADMIN_OPT_LAN_93", "bepaal of het e-mailadres van een auteur die geen lid van de site is mag worden getoond. geldt alleen als auteurs e-mailadres is ingesteld in bovenstaande sectie.");
define("CONTENT_ADMIN_OPT_LAN_94", "volgend vorig");
define("CONTENT_ADMIN_OPT_LAN_95", "toon volgende vorige knoppen");
define("CONTENT_ADMIN_OPT_LAN_96", "wanneer geactiveerd wordt maar een beperkt aantal onderwerpen getoond op de overzichtspagina's en kun je door de pagina's bladeren om andere onderwerpen te bekijken.");
define("CONTENT_ADMIN_OPT_LAN_97", "onderwerpen per pagina");
define("CONTENT_ADMIN_OPT_LAN_98", "hoeveel onderwerpen moeten per pagina worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_99", "deze instelling wordt alleen gebruikt als de volgende-vorige beperkingen zijn aangekruist");
define("CONTENT_ADMIN_OPT_LAN_100", "negeren afdruk/e-mail/pdf");
define("CONTENT_ADMIN_OPT_LAN_101", "toon afdruk/e-mail/pdf pictogrammen bij alle onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_102", "wanneer dit is geactiveerd, worden de pictogrammen bij alle onderwerpen getoond, ongeacht de instellingen bij de afzonderlijke onderwerpen en ouders");
define("CONTENT_ADMIN_OPT_LAN_103", "negeren beoordelingen systeem");
define("CONTENT_ADMIN_OPT_LAN_104", "toon het beoordelingsinstrument bij alle onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_105", "wanneer dit is geactiveerd, kunnen alle onderwerpen worden beoordeeld, ongeacht de instellingen bij de afzonderlijke onderwerpen en ouders");
define("CONTENT_ADMIN_OPT_LAN_106", "pagina sorteren/volgorde");
define("CONTENT_ADMIN_OPT_LAN_107", "kies een standaard soorteer en volgorde methode");
define("CONTENT_ADMIN_OPT_LAN_108", "sorteren op 'volgorde' ebruikt het volgordenummer die je hebt opgegeven in de Beheren volgorde sectie");
define("CONTENT_ADMIN_OPT_LAN_109", "kop (OPL)");
define("CONTENT_ADMIN_OPT_LAN_110", "kop (AFL)");
define("CONTENT_ADMIN_OPT_LAN_111", "datum (OPL)");
define("CONTENT_ADMIN_OPT_LAN_112", "datum (AFL)");
define("CONTENT_ADMIN_OPT_LAN_113", "verwijzer (OPL)");
define("CONTENT_ADMIN_OPT_LAN_114", "verwijzer (AFL)");
define("CONTENT_ADMIN_OPT_LAN_115", "ouder (OPL)");
define("CONTENT_ADMIN_OPT_LAN_116", "ouder (AFL)");
define("CONTENT_ADMIN_OPT_LAN_117", "volgorde (OPL)");
define("CONTENT_ADMIN_OPT_LAN_118", "volgorde (AFL)");
define("CONTENT_ADMIN_OPT_LAN_119", "content categorie pagina (content.php?type.X.cat.Y)");
define("CONTENT_ADMIN_OPT_LAN_120", "ouder onderwerp");
define("CONTENT_ADMIN_OPT_LAN_121", "moet het onderwerp van de ouder worden getoond");
define("CONTENT_ADMIN_OPT_LAN_122", "ouder subcategorieën");
define("CONTENT_ADMIN_OPT_LAN_123", "moeten de subcategorieën van de ouder worden getoond");
define("CONTENT_ADMIN_OPT_LAN_124", "wanneer geactiveerd, worden alle onderliggende subcategorieën met de oudercategorie getoond . Wanner gedeactiveerd worden uitsluitend de ouderonderwerpen getoond");
define("CONTENT_ADMIN_OPT_LAN_125", "ouder subcategorie onderwerp");
define("CONTENT_ADMIN_OPT_LAN_126", "moeten de onderwerpen van de ouder subcategorieën worden getoond");
define("CONTENT_ADMIN_OPT_LAN_127", "wanneer geactiveerd, worden alle onderwerpen van de geselecteerde categorie en de onderwerpen uit de onderliggende categorieën getoond. Wanneer gedeactiveerd, worden alleen de onderwerpen van de geselecteerde categorie getoond");
define("CONTENT_ADMIN_OPT_LAN_128", "volgorde ouder-kind");
define("CONTENT_ADMIN_OPT_LAN_129", "bepaal de volgorde van ouder en kind onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_130", "kies in welke volgorde de ouder en kind onderwerpen moeten worden getoond");
define("CONTENT_ADMIN_OPT_LAN_131", "ouder boven kinderen");
define("CONTENT_ADMIN_OPT_LAN_132", "kinderen boven ouder");
define("CONTENT_ADMIN_OPT_LAN_133", "weergavetype menus");
define("CONTENT_ADMIN_OPT_LAN_134", "kies een weergavemethode voor alle menus");
define("CONTENT_ADMIN_OPT_LAN_135", "je kunt de ouder weergeven, sub en kinder onderwerpen elk in een eigen menu, of je kunt ze combineren in een enkel menu");
define("CONTENT_ADMIN_OPT_LAN_136", "elk in een eigen menu");
define("CONTENT_ADMIN_OPT_LAN_137", "combineren in een enkel menu");
define("CONTENT_ADMIN_OPT_LAN_138", "content pagina (content.php?type.X.content.Y)");
define("CONTENT_ADMIN_OPT_LAN_139", "kies welke secties moeten worden getoond bij het bekijken van een content onderwerp");
define("CONTENT_ADMIN_OPT_LAN_140", "menu eigenschappen");
define("CONTENT_ADMIN_OPT_LAN_141", "titelbalk");
define("CONTENT_ADMIN_OPT_LAN_142", "bepaal de titel van het menu");
define("CONTENT_ADMIN_OPT_LAN_143", "zoeken");
define("CONTENT_ADMIN_OPT_LAN_144", "moet het zoekmenu worden getoond");
define("CONTENT_ADMIN_OPT_LAN_145", "sorteren en volgorde");
define("CONTENT_ADMIN_OPT_LAN_146", "moeten de sorteeropties worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_147", "links naar pagina's");
define("CONTENT_ADMIN_OPT_LAN_148", "link : alle categorieën");
define("CONTENT_ADMIN_OPT_LAN_149", "moet een link naar de 'alle categorieën' pagina worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_150", "link : all authors");
define("CONTENT_ADMIN_OPT_LAN_151", "moet een link naar de 'alle auteurs' pagina worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_152", "link : hoogst gewaardeerd");
define("CONTENT_ADMIN_OPT_LAN_153", "moet een link naar de 'hoogste gewaardeerde onderwerpen' pagina worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_154", "link : recente onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_155", "moet een link naar de 'recente content onderwerpen' pagina worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_156", "link : submit onderwerp");
define("CONTENT_ADMIN_OPT_LAN_157", "moet een link naar de 'aanmelden content onderwerp' pagina worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_158", "pictogram : links");
define("CONTENT_ADMIN_OPT_LAN_159", "bepaal het te tonen pictogram");
define("CONTENT_ADMIN_OPT_LAN_160", "geen (), bullet (), middot (&middot;), witte bullet (º), pijltje (&raquo;)");
define("CONTENT_ADMIN_OPT_LAN_161", "categorieën");
define("CONTENT_ADMIN_OPT_LAN_162", "subcategorieën");
define("CONTENT_ADMIN_OPT_LAN_163", "moeten de (sub) categorieën worden getoond als de aanwezig zijn?");
define("CONTENT_ADMIN_OPT_LAN_164", "aantal onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_165", "moet het totale aantal onderwerpen in elke categorie worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_166", "pictogram : categorie");
define("CONTENT_ADMIN_OPT_LAN_167", "geen (), bullet (), middot (&middot;), witte bullet (º), pijltje (&raquo;), category_icon()");
define("CONTENT_ADMIN_OPT_LAN_168", "geen");
define("CONTENT_ADMIN_OPT_LAN_169", "bullet");
define("CONTENT_ADMIN_OPT_LAN_170", "middot");
define("CONTENT_ADMIN_OPT_LAN_171", "witte bullet");
define("CONTENT_ADMIN_OPT_LAN_172", "pijltje");
define("CONTENT_ADMIN_OPT_LAN_173", "categorie pictogram");
define("CONTENT_ADMIN_OPT_LAN_174", "overzicht recent");
define("CONTENT_ADMIN_OPT_LAN_175", "recente onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_176", "moet een overzicht met recente aanvullingen worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_177", "titel : recent overzicht");
define("CONTENT_ADMIN_OPT_LAN_178", "bepaal de titel van het recente overzicht");
define("CONTENT_ADMIN_OPT_LAN_179", "aantal recente onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_180", "hoeveel recente onderwerpen moeten worden getoond");
define("CONTENT_ADMIN_OPT_LAN_181", "datum");
define("CONTENT_ADMIN_OPT_LAN_182", "moet de datum worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_183", "auteur");
define("CONTENT_ADMIN_OPT_LAN_184", "moet de auteur worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_185", "onderkop");
define("CONTENT_ADMIN_OPT_LAN_186", "moet de onderkop worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_187", "onderkop : tekens");
define("CONTENT_ADMIN_OPT_LAN_188", "hoeveel tekens van de onderkop moeten worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_189", "niet invullen om de hele onderkop te laten zien");
define("CONTENT_ADMIN_OPT_LAN_190", "onderkop : postfix");
define("CONTENT_ADMIN_OPT_LAN_191", "instellen postfix voor te lange oonderkoppen");
define("CONTENT_ADMIN_OPT_LAN_192", "pictogram : recente onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_193", "geen (), bullet (), middot (·), witte bullet (º), pijltje (»), content_icon()");
define("CONTENT_ADMIN_OPT_LAN_194", "content pictogram");
define("CONTENT_ADMIN_OPT_LAN_195", "pictogram : breedte");
define("CONTENT_ADMIN_OPT_LAN_196", "bepaal de breedte van het picrogram");
define("CONTENT_ADMIN_OPT_LAN_197", "if you have chosen to display the 'content icon', specify the width of the content icon to show. enter only a numeric value of the amount of pixels you want. do not add the 'px' to the number.");
define("CONTENT_ADMIN_OPT_LAN_200", "bijwerk opties");
define("CONTENT_ADMIN_OPT_LAN_201", "negeren reacties systeem");
define("CONTENT_ADMIN_OPT_LAN_202", "toestaan reacties voor alle onderwerpen");
define("CONTENT_ADMIN_OPT_LAN_203", "wanneer geactiveerd, is het mogelijk om reacties te plaatsen bij alle onderwerpen ongeacht de individuele instelling");
define("CONTENT_ADMIN_OPT_LAN_204", "bewerk pictogram: toon pictogram met link naar het beheerscherm om het onderwerp te bewerken");
define("CONTENT_ADMIN_OPT_LAN_205", "layout sjablonen");
define("CONTENT_ADMIN_OPT_LAN_206", "maatwerk gegevens");
define("CONTENT_ADMIN_OPT_LAN_207", "categorieën weergavetype");
define("CONTENT_ADMIN_OPT_LAN_208", "geef op hoe de categorieën moeten worden weergegeven");
define("CONTENT_ADMIN_OPT_LAN_209", "je kunt de categorieën met de overige links in de keuzebox plaatsen, of je kunt ze als normale links tonen");
define("CONTENT_ADMIN_OPT_LAN_210", "selectbox");
define("CONTENT_ADMIN_OPT_LAN_211", "normalelinks");
define("CONTENT_ADMIN_OPT_LAN_212", "link : alle contentonderwerpen");
define("CONTENT_ADMIN_OPT_LAN_213", "moet een link naar de 'alle content onderwerpen' pagina (de archief pagina) worden getoond?");
define("CONTENT_ADMIN_OPT_LAN_214", "datumstijl");
define("CONTENT_ADMIN_OPT_LAN_215", "kies een datumstijl voor het weergeven van de datum");
define("CONTENT_ADMIN_OPT_LAN_216", "Voor meer informatie over datumformaten zie de <a href='http://www.php.net/manual/en/function.strftime.php' rel='external'>strftime functie pagina van php.net</a>");
define("CONTENT_ADMIN_OPT_LAN_217", "archiefpagina");

define("CONTENT_ADMIN_ITEM_LAN_0", "verplicht veld niet ingevuld");
define("CONTENT_ADMIN_ITEM_LAN_1", "onderwerp aangemaakt");
define("CONTENT_ADMIN_ITEM_LAN_2", "onderwerp bijgewerkt");
define("CONTENT_ADMIN_ITEM_LAN_3", "onderwerp verwijderd");
define("CONTENT_ADMIN_ITEM_LAN_4", "nog geen onderwerpen");
define("CONTENT_ADMIN_ITEM_LAN_5", "aanwezige onderwerpen");
define("CONTENT_ADMIN_ITEM_LAN_6", "eerste letters");
define("CONTENT_ADMIN_ITEM_LAN_7", "kies hierboven een letter.");
define("CONTENT_ADMIN_ITEM_LAN_8", "id");
define("CONTENT_ADMIN_ITEM_LAN_9", "pictogram");
define("CONTENT_ADMIN_ITEM_LAN_10", "auteur");
define("CONTENT_ADMIN_ITEM_LAN_11", "kop");
define("CONTENT_ADMIN_ITEM_LAN_12", "opties");
define("CONTENT_ADMIN_ITEM_LAN_13", "kies categorie van de ouder");
define("CONTENT_ADMIN_ITEM_LAN_14", "naam auteur");
define("CONTENT_ADMIN_ITEM_LAN_15", "e-mailadres auteur");
define("CONTENT_ADMIN_ITEM_LAN_16", "onderkop");
define("CONTENT_ADMIN_ITEM_LAN_17", "samenvatting");
define("CONTENT_ADMIN_ITEM_LAN_18", "tekst");
define("CONTENT_ADMIN_ITEM_LAN_19", "upload pictogram");
define("CONTENT_ADMIN_ITEM_LAN_20", "content pictogram");
define("CONTENT_ADMIN_ITEM_LAN_21", "Deze optie is gedeactiveerd, omdat het niet is toegestaan bestanden naar je server te uploaden");
define("CONTENT_ADMIN_ITEM_LAN_22", "De");
define("CONTENT_ADMIN_ITEM_LAN_23", "map is niet beschijfbaar. Stel de permissies voor uploaden in op CHMOD 777");
define("CONTENT_ADMIN_ITEM_LAN_24", "upload bijlagen");
define("CONTENT_ADMIN_ITEM_LAN_25", "upload nieuw pictogram");
define("CONTENT_ADMIN_ITEM_LAN_26", "verwijder");
define("CONTENT_ADMIN_ITEM_LAN_27", "huidige content bestand");
define("CONTENT_ADMIN_ITEM_LAN_28", "upload nieuw bestand");
define("CONTENT_ADMIN_ITEM_LAN_29", "nog geen bestand");
define("CONTENT_ADMIN_ITEM_LAN_30", "content bestand");
define("CONTENT_ADMIN_ITEM_LAN_31", "upload afbeeldingen");
define("CONTENT_ADMIN_ITEM_LAN_32", "huidige content afbeelding");
define("CONTENT_ADMIN_ITEM_LAN_33", "upload nieuwe afbeelding");
define("CONTENT_ADMIN_ITEM_LAN_34", "content afbeelding");
define("CONTENT_ADMIN_ITEM_LAN_35", "voorkeuren voor dit onderwerp instellen");
define("CONTENT_ADMIN_ITEM_LAN_36", "reageren toestaan");
define("CONTENT_ADMIN_ITEM_LAN_37", "beoordelen toestaan");
define("CONTENT_ADMIN_ITEM_LAN_38", "tonen afdruk/e-mail pictogrammen");
define("CONTENT_ADMIN_ITEM_LAN_39", "zichtbaarheid");
define("CONTENT_ADMIN_ITEM_LAN_40", "score");
define("CONTENT_ADMIN_ITEM_LAN_41", "kies een score ...");
define("CONTENT_ADMIN_ITEM_LAN_42", "aankruisen om datum en tijd op de huidige datum en tijd in te stellen");
define("CONTENT_ADMIN_ITEM_LAN_43", "plaats door gebruiker aangemeld content onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_44", "creëren content onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_45", "bijwerken content onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_46", "bekijken");
define("CONTENT_ADMIN_ITEM_LAN_47", "nogmaals bekijken");
define("CONTENT_ADMIN_ITEM_LAN_48", "hoofdouder");
define("CONTENT_ADMIN_ITEM_LAN_49", "aangemelde content onderwerpen");
define("CONTENT_ADMIN_ITEM_LAN_50", "geen aangemelde content onderwerpen");
define("CONTENT_ADMIN_ITEM_LAN_51", "auteursgegevens<br /> niet invullen als je zelf de schrijver bentyou");
define("CONTENT_ADMIN_ITEM_LAN_52", "aanmelden content onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_53", "meta sleutelwoorden voor dit content onderwerp (scheiden met comma's, spaties niet toegestaan !)");
define("CONTENT_ADMIN_ITEM_LAN_54", "aanvullende gegevens");
define("CONTENT_ADMIN_ITEM_LAN_55", "Terug naar de  <a href='".e_SELF."'>hoofd contentmanager pagina</a> om meer van je persoonlijke content te beheren<br />or<br />Ga naar de <a href='".e_PLUGIN."content/content.php'>hoofd content pagina</a> om de onderwerpen te bekijken.");
define("CONTENT_ADMIN_ITEM_LAN_56", "persoonlijke content manager");
define("CONTENT_ADMIN_ITEM_LAN_57", "categorie");
define("CONTENT_ADMIN_ITEM_LAN_58", "onderwerpen");
define("CONTENT_ADMIN_ITEM_LAN_59", "verplaats");
define("CONTENT_ADMIN_ITEM_LAN_60", "volgorde");
define("CONTENT_ADMIN_ITEM_LAN_61", "bijwerken volgorde");
define("CONTENT_ADMIN_ITEM_LAN_62", "sorteren categorieën");
define("CONTENT_ADMIN_ITEM_LAN_63", "meer");
define("CONTENT_ADMIN_ITEM_LAN_64", "minder");
define("CONTENT_ADMIN_ITEM_LAN_65", "sorteren onderwerpen");
define("CONTENT_ADMIN_ITEM_LAN_66", "Hieronder die je de startletters van de content kopteksten van alle onderwerpen in deze categorie.<br />Door het klikken op een letter zie je vervolgens een lijst met alle onderwerpen die beginnen met die letter. Je kunt ook de ALLE knop indrukken om alle onderwerpen in deze categorie te zien.");
define("CONTENT_ADMIN_ITEM_LAN_67", "Hieronder zie je de lijst met contentonderwerpen in de geselecteerde categorie of die beginnen met de gekozen letter.<br />Je kunt onderwerpen bewerken of verwijderen door op de betreffende knop rechts te drukken.");
define("CONTENT_ADMIN_ITEM_LAN_68", "Hieronder kun je maatwerk informatie toevoegen bij dit contentonderwerp. Alle maatwerkgegevens moeten zowel een sleutel als een waarde hebben. Je kunt sleutel in het linker veld definiëren en de bijbehorende waarde in het rechterveld.<br />(bij voorbeeld: sleutel='fotografie' en waarde ='al mijn eigen foto's'.");
define("CONTENT_ADMIN_ITEM_LAN_69", "Hier kun je picrogrammen, bijlagen en-of afbeeldingen uploaden die bij het onderwerp horen. De toegestane bestandsformaten zijn: ");
define("CONTENT_ADMIN_ITEM_LAN_70", "In het volgende veld kun je bepaalde meta sleutelwoorden die bij dit onderwerp horen opgeven. Deze sleutelwoorden worden bovenaan de pagine weergegeven. De verschillende worden moeten met comma's worden gescheiden, spaties zijn niet toegestaan!");
define("CONTENT_ADMIN_ITEM_LAN_71", "laten staan al jij het onderwerp hebt geschreven");
define("CONTENT_ADMIN_ITEM_LAN_72", "bepalen auteursgegevens");
define("CONTENT_ADMIN_ITEM_LAN_73", "opgeven startdatum voor dit onderwerp (niet invullen als niet benodigd)");
define("CONTENT_ADMIN_ITEM_LAN_74", "opgeven einddatum voor dit onderwerp (niet invullen als niet benodigd)");
define("CONTENT_ADMIN_ITEM_LAN_75", "uploaden en toekennen onderwerppictogram");
define("CONTENT_ADMIN_ITEM_LAN_76", "uploaden en koppelen bijlage bij dit onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_77", "uploaden en koppelen afbeeldingen bij dit onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_78", "instellen of reacties toegestaan zijn");
define("CONTENT_ADMIN_ITEM_LAN_79", "instellen of beoordelen mogelijk moet zijn");
define("CONTENT_ADMIN_ITEM_LAN_80", "instellen of de afdruk/e-mail pictogrammen moeten worden getoond");
define("CONTENT_ADMIN_ITEM_LAN_81", "bepaal welke gebruikers dit onderwerp mogen zien");
define("CONTENT_ADMIN_ITEM_LAN_82", "bepaal een score");
define("CONTENT_ADMIN_ITEM_LAN_83", "opgeven meta sleutelwoorden");
define("CONTENT_ADMIN_ITEM_LAN_84", "opgeven maatwerk gegevensvelden (sleutel + waarde)");
define("CONTENT_ADMIN_ITEM_LAN_85", "geactiveerd");
define("CONTENT_ADMIN_ITEM_LAN_86", "gedeactiveerd");
define("CONTENT_ADMIN_ITEM_LAN_87", "kies een pictogram voor dit onderwerp");
define("CONTENT_ADMIN_ITEM_LAN_88", "om een onderwerp in de eerder geselecteerde hoofdcategorie te maken");
define("CONTENT_ADMIN_ITEM_LAN_89", "om een onderwerp in de eerder geselecteerde hoofdcategorie te bewerken");
define("CONTENT_ADMIN_ITEM_LAN_90", "klik hier");
define("CONTENT_ADMIN_ITEM_LAN_91", "om ditzelfde onderwerp opnieuw te bewerken");
define("CONTENT_ADMIN_ITEM_LAN_92", "sjabloon");
define("CONTENT_ADMIN_ITEM_LAN_93", "kies een layout sjabloon");
define("CONTENT_ADMIN_ITEM_LAN_94", "selecteer een layout sjabloon");

define("CONTENT_ADMIN_ORDER_LAN_0", "volgorde is verhoogd");
define("CONTENT_ADMIN_ORDER_LAN_1", "volgorde is verlaagd");
define("CONTENT_ADMIN_ORDER_LAN_2", "nieuwe volgorde voor onderwerpen is opgeslagen");

define("CONTENT_ADMIN_MAIN_LAN_0", "aanwezige content categorieën");
define("CONTENT_ADMIN_MAIN_LAN_1", "nog geen content categorieën");
define("CONTENT_ADMIN_MAIN_LAN_2", "hoofdcategorieën");
define("CONTENT_ADMIN_MAIN_LAN_3", "content onderwerp verwijderd");
define("CONTENT_ADMIN_MAIN_LAN_4", "tekst ouder");
define("CONTENT_ADMIN_MAIN_LAN_5", "pictogram ouder");
define("CONTENT_ADMIN_MAIN_LAN_7", "Welkom bij het Content Management System !");
define("CONTENT_ADMIN_MAIN_LAN_8", "Lees eerst nauwkeurig de onderstaande informatie en maak een keuze.");
define("CONTENT_ADMIN_MAIN_LAN_9", "Deze informatie wordt getoond, omdat de tabel van de Content Management Plugin geen records bevat.");
define("CONTENT_ADMIN_MAIN_LAN_10", "Je kunt de content beheren op deze pagina. Bepaal eerst in welke categorie je content wilt beheren. Klik op de knop van de hoofd ouders die hieronder staan om de content voor die categorie te beheren.");
define("CONTENT_ADMIN_MAIN_LAN_11", "
<b>De oude content tabel bevat records</b><br />
Omdat de oude tabel al records bevat , kun je nu kiezen uit twee mogelijkheden:<br />
<br />
<b>a) converteren oude records</b><br />
Het eerste dat je moet doen is het maken van een backup van je huidige content tabel alsmede van je reacties en waarderingstabellen.<br />
Gebruik een apart programma voor het maken van de backup, bijvoorbeeld phpmyadmin.<br />
Nadat je de backup van je oude content tabel hebt gemaakt, kun je de content converteren naar de nieuwe Content Management Plugin.<br />
Na de conversie zul je deze informatie niet meer terugzien en kun je met het beheer beginnen.<br />
Ga naar de <a href='".e_PLUGIN."content/admin_content_convert.php'>Content conversie script</a> pagina.<br />
<br />
<b>b) niet converteren en alleen de nieuwe content beheren</b><br />
Als je de bestaande content niet meer nodig hebt, ,<br />
en een nieuwe Content Management Plugin tabel wilt aanmaken,<br />
dan kun je gewoon beginnen door het creëren van een nieuwe categorie.<br />
Ga naar de <a href='".e_SELF."?type.0.cat.create'>Creëren nieuwe categorie</a> pagina.<br />");
define("CONTENT_ADMIN_MAIN_LAN_12", "<b>Dit is een nieuwe installatie / De oude content tabel bevat geen records</b><br />
Omdat de oude tabel geen records bevat, kun je nu meteen beginnen met het beheer van je nieuwe content.<br />
Het eerste wat je moet doen is het creëren van een nieuwe categorie.<br />
Ga naar de <a href='".e_SELF."?type.0.cat.create'>Creëren nieuwe categorie</a> pagina.<br />");
define("CONTENT_ADMIN_MAIN_LAN_13", "Je kunt nieuwe content beheren op deze pagina. Bepaal eerst de categorie waarvoor je content wilt beheren. Klik op de knop van de hoofd ouders die hieronder staan om de content voor die categorie te beheren.");
define("CONTENT_ADMIN_MAIN_LAN_14", "Je kunt op deze pagina de volgorde van de content vaststellen. Klik op de knop van de hoofd ouders die hieronder staan om de volgorde van content of categorieën binnen de geselecteerde hoofdcategorie te wijzigen.");
define("CONTENT_ADMIN_MAIN_LAN_15", "Op deze pagina kun je de categorieën beheren. Kies de hoofdcategorie hieronder om het overzicht van alle categorieën en subcategorieën binnen deze hoofdcategorie te zien.");
define("CONTENT_ADMIN_MAIN_LAN_16", "Op deze pagina kun je nieuwe categorieën aanmaken. Standaard wordt het invulformulier voor een nieuwe hoofdcategorie getoond. Als je een subcategorie voor een al aanwezige hoofdcategorie wilt maken, klik dan op een van de onderstaande knoppen om het formulier voor een subcategorie binnen die hoofdcategorie te zien.");
define("CONTENT_ADMIN_MAIN_LAN_17", "maak een nieuwe categorie op de <a href='".e_SELF."?type.0.cat.create'>Creëren nieuwe categorie</a> pagina");
define("CONTENT_ADMIN_MAIN_LAN_18", "Converteren records");
define("CONTENT_ADMIN_MAIN_LAN_19", "Het eerste wat je moet doen, is het maken van een backup van de huidige content tabel en van je reacties (comments) en waarderingstabellen (rate table).<br />
Gebruik een programma als phpmyadmin om backups van deze tabellen te maken.<br />
Nadat je de backups hebt gemaakt, kun je beginnen met het converteren van de tabellen naar de nieuwe Content Management Plugin.<br />
Na conversie van de oude content zul je deze informatie niet meer zien en kun je gewoon je content beheren.<br />");
define("CONTENT_ADMIN_MAIN_LAN_20", "Starten met een lege content tabel");
define("CONTENT_ADMIN_MAIN_LAN_21", "Als je de gegevens uit de huidige content tabel niet meer nodig hebt<br />
en gewoon met een nieuwe Content Management Plugin tabel wilt beginnen,<br />
en ook geen set met standaard categorieën wilt starten,<br />
dan kun je beginnen met het aanmaken van een nieuwe contentcategorie.<br />");
define("CONTENT_ADMIN_MAIN_LAN_22", "Aanmaken set standaardcategorieën");
define("CONTENT_ADMIN_MAIN_LAN_23", "Als je met een nieuwe installatie wilt starten, kun je eerst een set standaard contentcategorieën creëren.<br />
In deze standaard set worden drie hoofd oudercategorieën aangemaakt, namelijk Content, Review en Artikel.<br />");
define("CONTENT_ADMIN_MAIN_LAN_24", "Dit is een nieuwe installatie / De oude content tabel bevat geen record");
define("CONTENT_ADMIN_MAIN_LAN_25", "Omdat de oude aanwezige content tabel nog geen records bevat, kun je nu gewoon beginnen met het beheren van nieuwe content.<br />
Door te klikken op de Volgende knop, maak je automatisch een nieuwe standaard set categorieën aan, namelijk Content, Review en Artikel.<br />");

define("CONTENT_ADMIN_MENU_LAN_0", "Beheren aanwezige content");
define("CONTENT_ADMIN_MENU_LAN_1", "Aanmaken nieuwe content");
define("CONTENT_ADMIN_MENU_LAN_2", "Beheren aanwezige categorieën");
define("CONTENT_ADMIN_MENU_LAN_3", "Creëren nieuwe categorie");
define("CONTENT_ADMIN_MENU_LAN_4", "Aangemelden onderwerpen");
define("CONTENT_ADMIN_MENU_LAN_5", "Categorie");
define("CONTENT_ADMIN_MENU_LAN_6", "Opties");
define("CONTENT_ADMIN_MENU_LAN_7", "Creëren");
define("CONTENT_ADMIN_MENU_LAN_8", "Aanmelden");
define("CONTENT_ADMIN_MENU_LAN_9", "Pad en thema");
define("CONTENT_ADMIN_MENU_LAN_10", "Algemeen");
define("CONTENT_ADMIN_MENU_LAN_11", "Recente pagina's");
define("CONTENT_ADMIN_MENU_LAN_12", "Categorie pagina's");
define("CONTENT_ADMIN_MENU_LAN_13", "Content pagina's");
define("CONTENT_ADMIN_MENU_LAN_14", "Menu");
define("CONTENT_ADMIN_MENU_LAN_15", "Beheren volgorde");
define("CONTENT_ADMIN_MENU_LAN_16", "Archiefpagina");

define("CONTENT_ADMIN_JS_LAN_0", "Weet je zeker dat je deze categorie wilt verwijderen?");
define("CONTENT_ADMIN_JS_LAN_1", "Weet je zeker dat je dit onderwerp wilt verwijderen?");
define("CONTENT_ADMIN_JS_LAN_2", "weet je zeker dat je de huidige afbeelding wilt verwijderen?");
define("CONTENT_ADMIN_JS_LAN_3", "weet je zeker dat je het huidige bestand wilt verwijderen?");
define("CONTENT_ADMIN_JS_LAN_4", "afbeelding");
define("CONTENT_ADMIN_JS_LAN_5", "bestand");
define("CONTENT_ADMIN_JS_LAN_6", "ID");
define("CONTENT_ADMIN_JS_LAN_7", "weet je zeker dat je het huidige pictogram wilt verwijderen?");
define("CONTENT_ADMIN_JS_LAN_8", "pictogram");
define("CONTENT_ADMIN_JS_LAN_9", "WAARSCHUWING :\\ndoor deze categorie te verwijderen, worden alle eventuele aanwezige subcategorieën,\\nook verwijderd!");
define("CONTENT_ADMIN_JS_LAN_10", "Weet je zeker dat je dit aangemelde onderwerp, dat nog niet werd geplaatst, wilt verwijderen?");

define("CONTENT_ADMIN_DATE_LAN_0", "Januari");
define("CONTENT_ADMIN_DATE_LAN_1", "Februari");
define("CONTENT_ADMIN_DATE_LAN_2", "Maart");
define("CONTENT_ADMIN_DATE_LAN_3", "April");
define("CONTENT_ADMIN_DATE_LAN_4", "Mei");
define("CONTENT_ADMIN_DATE_LAN_5", "Juni");
define("CONTENT_ADMIN_DATE_LAN_6", "Juli");
define("CONTENT_ADMIN_DATE_LAN_7", "Augustus");
define("CONTENT_ADMIN_DATE_LAN_8", "September");
define("CONTENT_ADMIN_DATE_LAN_9", "Oktober");
define("CONTENT_ADMIN_DATE_LAN_10", "November");
define("CONTENT_ADMIN_DATE_LAN_11", "December");
define("CONTENT_ADMIN_DATE_LAN_12", "dag");
define("CONTENT_ADMIN_DATE_LAN_13", "maand");
define("CONTENT_ADMIN_DATE_LAN_14", "jaar");
define("CONTENT_ADMIN_DATE_LAN_15", "installen maatwerk startdatum");
define("CONTENT_ADMIN_DATE_LAN_16", "instellen einddatum");
define("CONTENT_ADMIN_DATE_LAN_17", "je kunt een startdatum voor dit onderwerp opgeven. niet invullen voor de huidige tijd");
define("CONTENT_ADMIN_DATE_LAN_18", "je kunt een einddatum voor dit onderwerp opgeven. niet invullen voor onbepaalde duur");

define("CONTENT_LAN_0", "Content");
define("CONTENT_LAN_1", "Overzicht recent");
define("CONTENT_LAN_2", "Overzicht categorie");
define("CONTENT_LAN_3", "Categorie");
define("CONTENT_LAN_4", "Overzicht auteurs");
define("CONTENT_LAN_5", "Auteur");
define("CONTENT_LAN_6", "alle categorieën");
define("CONTENT_LAN_7", "alle auteurs");
define("CONTENT_LAN_8", "hoogst gewaardeerd");
define("CONTENT_LAN_9", "sorteren op ...");

define("CONTENT_LAN_10", "koptekst_op");
define("CONTENT_LAN_11", "koptekst_neer");
define("CONTENT_LAN_12", "datum_op");
define("CONTENT_LAN_13", "datum_neer");
define("CONTENT_LAN_14", "verwijs_op");
define("CONTENT_LAN_15", "verwijs_neer");
define("CONTENT_LAN_16", "ouder_op");
define("CONTENT_LAN_17", "ouder_neer");
define("CONTENT_LAN_18", "zoek naar sleutelwoord");
define("CONTENT_LAN_19", "zoeken");

define("CONTENT_LAN_20", "content zoekresultaten");
define("CONTENT_LAN_21", "nog geen content types.");
define("CONTENT_LAN_22", "content types");
define("CONTENT_LAN_23", "content recent overzicht");
define("CONTENT_LAN_24", "kruimelpad");
define("CONTENT_LAN_25", "content categorieën");
define("CONTENT_LAN_26", "ouder");
define("CONTENT_LAN_27", "subcategorieën");
define("CONTENT_LAN_28", "subcategorieën ouder");
define("CONTENT_LAN_29", "onbekend");
define("CONTENT_LAN_30", "contentonderwerp");
define("CONTENT_LAN_31", "contentonderwerpen");
define("CONTENT_LAN_32", "overzicht contentauteurs");
define("CONTENT_LAN_33", "Ga naar pagina");
define("CONTENT_LAN_34", "content");
define("CONTENT_LAN_35", "reacties");
define("CONTENT_LAN_36", "beheren reacties");
define("CONTENT_LAN_37", "nog geen contentonderwerpen beoordeeld");
define("CONTENT_LAN_38", "hoogst gewaardeerde contentonderwerpen");
define("CONTENT_LAN_39", "auteursoverzicht");
define("CONTENT_LAN_40", "auteursgegevens");
define("CONTENT_LAN_41", "download bijgevoegd");
define("CONTENT_LAN_42", "bestand");
define("CONTENT_LAN_43", "bestanden");
define("CONTENT_LAN_44", "hits:");
define("CONTENT_LAN_45", "auteur toegekende score:");
define("CONTENT_LAN_46", "artikel index");
define("CONTENT_LAN_47", "auteur");
define("CONTENT_LAN_48", "contentonderwerpen");
define("CONTENT_LAN_49", "laatste contentonderwerp");
define("CONTENT_LAN_50", "datum");
define("CONTENT_LAN_51", "Type overzicht");
define("CONTENT_LAN_52", "geen geldige auteurs gevonden");
define("CONTENT_LAN_53", "onderwerp");
define("CONTENT_LAN_54", "onderwerpen");
define("CONTENT_LAN_55", "laatste onderwerp op");
define("CONTENT_LAN_56", "toon overzicht van");
define("CONTENT_LAN_57", "reacties:");
define("CONTENT_LAN_58", "start");
define("CONTENT_LAN_59", "content");
define("CONTENT_LAN_60", "recente");
define("CONTENT_LAN_61", "bekijk recente onderwerpen");
define("CONTENT_LAN_62", "bekijk alle categorieën");
define("CONTENT_LAN_63", "bekijk alle auteurs");
define("CONTENT_LAN_64", "bekijk hoogst gewaardeerde onderwerpen");
define("CONTENT_LAN_65", "aanmelden content");
define("CONTENT_LAN_66", "klik hier om content aan te melden, je kunt de categorie kiezen op de aanmeldpagina.");
define("CONTENT_LAN_67", "persoonlijke contentbeheerder");
define("CONTENT_LAN_68", "klik hier om je persoonlijke content te beheren.");
define("CONTENT_LAN_69", "e-mailen van het");
define("CONTENT_LAN_70", "afdrukken van het");
define("CONTENT_LAN_71", "content onderwerp");
define("CONTENT_LAN_72", "categorie onderwerp");
define("CONTENT_LAN_73", "order_asc");
define("CONTENT_LAN_74", "order_desc");
define("CONTENT_LAN_75", "aanmelden content onderwerp");
define("CONTENT_LAN_76", "creëren van een pdf bestand van het");
define("CONTENT_LAN_77", "content zoeken");
define("CONTENT_LAN_78", "pagina zonder titel");
define("CONTENT_LAN_79", "pagina");
define("CONTENT_LAN_80", "recente onderwerpen: ");
define("CONTENT_LAN_81", "categorieën");
define("CONTENT_LAN_82", "nog geen onderwerpen in ");
define("CONTENT_LAN_83", "onderwerparchief");
define("CONTENT_LAN_84", "content archief");

define("CONTENT_ADMIN_SUBMIT_LAN_0", "geen van de inhoudscategorieën is beschikbaar voor aanmelding door gewone leden");
define("CONTENT_ADMIN_SUBMIT_LAN_1", "inhoud aanmeldingstype");
define("CONTENT_ADMIN_SUBMIT_LAN_2", "Bedankt, je onderwerp is aangemeld.");
define("CONTENT_ADMIN_SUBMIT_LAN_3", "Bedankt, je onderwerp is aangemeld en wordt z.s.m. beoordeeld door een beheerder.");
define("CONTENT_ADMIN_SUBMIT_LAN_4", "verplicht(e) veld(en) niet ingevuld");
define("CONTENT_ADMIN_SUBMIT_LAN_5", "Ga terug naar de <a href='".e_SELF."'>hoofd aanmeldingspagina</a> om meer inhoud aan te melden<br />or<br />Ga naar de <a href='".e_PLUGIN."content/content.php'>inhoud hoofdpagina</a> om de inhoud te zien.");
define("CONTENT_ADMIN_SUBMIT_LAN_6", "Inhoud type overzicht");
define("CONTENT_ADMIN_SUBMIT_LAN_7", "Inhoud type aanmelding");
define("CONTENT_ADMIN_SUBMIT_LAN_8", "aangemeld content onderwerp verwijderd");

define("CONTENT_ADMIN_CONVERSION_LAN_0", "inhoud");
define("CONTENT_ADMIN_CONVERSION_LAN_1", "review");
define("CONTENT_ADMIN_CONVERSION_LAN_2", "artikel");
define("CONTENT_ADMIN_CONVERSION_LAN_3", "categorie");
define("CONTENT_ADMIN_CONVERSION_LAN_4", "categorieën");
define("CONTENT_ADMIN_CONVERSION_LAN_5", "pagina");
define("CONTENT_ADMIN_CONVERSION_LAN_6", "pagina's");
define("CONTENT_ADMIN_CONVERSION_LAN_7", "hoofdpagina ingevoegd");
define("CONTENT_ADMIN_CONVERSION_LAN_8", "voorkeuren hoofdpagina ingevoegd");
define("CONTENT_ADMIN_CONVERSION_LAN_9", "geen");
define("CONTENT_ADMIN_CONVERSION_LAN_10", "hoofdpagina nodig");
define("CONTENT_ADMIN_CONVERSION_LAN_11", "CONVERSIE ANALYSE");
define("CONTENT_ADMIN_CONVERSION_LAN_12", "aantal te converteren regels");
define("CONTENT_ADMIN_CONVERSION_LAN_13", "aantal geconverteerde regels");
define("CONTENT_ADMIN_CONVERSION_LAN_14", "aantal waarschuwingen");
define("CONTENT_ADMIN_CONVERSION_LAN_15", "aantal fouten");
define("CONTENT_ADMIN_CONVERSION_LAN_16", "OUDE INHOUD TABEL : ANALYSE");
define("CONTENT_ADMIN_CONVERSION_LAN_17", "aantal regels");
define("CONTENT_ADMIN_CONVERSION_LAN_18", "onbekende regels");
define("CONTENT_ADMIN_CONVERSION_LAN_19", "alle regels zijn bekend");
define("CONTENT_ADMIN_CONVERSION_LAN_20", "INHOUD HOOFDPAGINA");
define("CONTENT_ADMIN_CONVERSION_LAN_21", "REVIEW HOOFDPAGINA");
define("CONTENT_ADMIN_CONVERSION_LAN_22", "ARTIKEL HOOFDPAGINA");
define("CONTENT_ADMIN_CONVERSION_LAN_23", "invoegen mislukt");
define("CONTENT_ADMIN_CONVERSION_LAN_24", "GEEN INHOUDPAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_25", "INHOUDPAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_26", "ingevoegd");
define("CONTENT_ADMIN_CONVERSION_LAN_27", "conversie analyse");
define("CONTENT_ADMIN_CONVERSION_LAN_28", "aantal oude regels");
define("CONTENT_ADMIN_CONVERSION_LAN_29", "aantal nieuwe regels");
define("CONTENT_ADMIN_CONVERSION_LAN_30", "mislukt");
define("CONTENT_ADMIN_CONVERSION_LAN_31", "waarschuwing");
define("CONTENT_ADMIN_CONVERSION_LAN_32", "oude categorie bestaat niet: onderwerpen toegevoegd aan hogere categorie");
define("CONTENT_ADMIN_CONVERSION_LAN_33", "nieuwe categorie bestaat niet: onderwerpen toegevoegd aan hogere categorie");
define("CONTENT_ADMIN_CONVERSION_LAN_34", "GEEN REVIEW CATEGORIE PAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_35", "REVIEW CATEGORIE PAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_36", "GEEN REVIEW PAGINA'S EN/OF AANGEMELDE REVIEW PAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_37", "REVIEW PAGINA'S EN/OF AANGEMELDE REVIEW PAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_38", "GEEN ARTIKELCATEGORIE PAGINA AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_39", "ARTIKELCATEGORIE PAGINA AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_40", "GEEN ARTIKELPAGINA'S EN/OF AANGEMELDE ARTIKELPAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_41", "ARTIKELPAGINA'S EN/OF AANGEMELDE ARTIKELPAGINA'S AANWEZIG");
define("CONTENT_ADMIN_CONVERSION_LAN_42", "conversie resultaten van de oude inhoudtabel naar de nieuwe inhoud plugin tabel");
define("CONTENT_ADMIN_CONVERSION_LAN_43", "druk op de knop om de oude inhoud tabel te converteren");
define("CONTENT_ADMIN_CONVERSION_LAN_44", "de nieuwe inhoud tabel bevat al gegevens !<br />weet je zeker dat je de oude inhoudtabel naar de nieuwe inhoudtabel wilt converteren ?<br /><br />als je dat nog steeds wilt, zal de oude inhoud aan de al aanwezige nieuwe inhoudtabel worden toegevoegd, maar er wordt niet gegarandeerd dat alle onderwerpen goed aan de nieuwe categorieën kunnen worden gekoppeld !");
define("CONTENT_ADMIN_CONVERSION_LAN_45", "invoegen mislukt: hoofdpagina niet ingevoegd");
define("CONTENT_ADMIN_CONVERSION_LAN_46", "Beheer je content op de <a href='".e_PLUGIN."content/admin_content_config.php'>Content Manager Plugin hoofdpagina</a> !");
define("CONTENT_ADMIN_CONVERSION_LAN_47", "conversie gereed");
define("CONTENT_ADMIN_CONVERSION_LAN_48", "klik hier voor details");
define("CONTENT_ADMIN_CONVERSION_LAN_49", "conversie van pagina's");
define("CONTENT_ADMIN_CONVERSION_LAN_50", "conversie van hoofdouders");
define("CONTENT_ADMIN_CONVERSION_LAN_51", "onbekende rijen");
define("CONTENT_ADMIN_CONVERSION_LAN_52", "standaard set van hoofdouders aangemaakt");
define("CONTENT_ADMIN_CONVERSION_LAN_53", "er bestaat al een hoofdouder met deze naam");
define("CONTENT_ADMIN_CONVERSION_LAN_54", "aanmaken van een standaard set met hoofdcategorieën (content, review en artikelen)");
define("CONTENT_ADMIN_CONVERSION_LAN_55", "content management plugin : converteer opties");
define("CONTENT_ADMIN_CONVERSION_LAN_56", "druk op de knop om naar de Content Creëren nieuwe categorie pagina te gaan.");
define("CONTENT_ADMIN_CONVERSION_LAN_57", "kies ouder");
?>