<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Dutch-iso/lan_content_help.php,v $
|     $Revision: 1.4 $
|     $Date: 2005-10-21 15:39:39 $
|     $Author: mijnheer $
+----------------------------------------------------------------------------+
*/

define("CONTENT_ADMIN_HELP_1", "Contentbeheer hulp");

define("CONTENT_ADMIN_HELP_ITEM_1", "<i>als je nog geen hoofdoudercategorie�n hebt aangemaakt, doe dat dan nu op de <a href='".e_SELF."?type.0.cat.create'>Cre�ren nieuwe categorie</a> pagina.</i><br /><br />
<b>categorie</b><br />selecteer een categorie uit het uitklapmenu om de content voor die categorie te beheren.<br /><br />
de hoofdouders zijn vet weergegeven en hebben de (ALLE) extensie. het kiezen hiervan toont alle onderwerpen bij deze hoofdouder.<br /><br />
voor iedere hoofdouder worden alle subcategorie�n getoond, inclusief de hoofdoudercategorie zelf (deze worden in normale tekstopmaak getoond). Het kiezen van een van deze categorie�n toont alleen de onderwerpen uit de betreffende categorie.");

define("CONTENT_ADMIN_HELP_ITEM_2", "<b>eerste letters</b><br />als er meerdere content onderwerpen beginnen met dezelfde letter in de kop, dan zie je de beginletters staan, waardoor je de onderwerpen kunt selecteren die met die letter beginnen. Het drukken op de 'alle' knop toont alle onderwerpen in deze categorie.<br /><br />
<b>detail overzicht</b><br />Je ziet het overzicht van alle onderwerpen met hun id, pictogram, auteur, kop [onderkop] en opties.<br /><br /><b>uitleg van de gebruikte pictogrammen</b><br />".CONTENT_ICON_EDIT." : bewerk het contentonderwerp.<br />".CONTENT_ICON_DELETE." : verwijder het contentonderwerp.<br />");

define("CONTENT_ADMIN_HELP_ITEMEDIT_1", "<b>bewerk formulier</b><br />je kunt nu alle informatie voor dit contentonderwerp invullen en je wijzigingen aanmelden.<br /><br />Als je de categorie van dit contentonderwerp verandert in een andere hoofdoudercategorie, zul je dit onderwerp waarschijnlijk willen bewerken na de categoriewijziging.<br />Als je een categorie wijzigt, kunnen er andere instellingen gelden waardoor je meer of minder velden in kunt vullen.");

define("CONTENT_ADMIN_HELP_ITEMCREATE_1", "<b>categorie</b><br />selecteer de categorie uit de keuzelijst waarvoor je een contentonderwerp wilt aanmaken.<br />");

define("CONTENT_ADMIN_HELP_ITEMCREATE_2", "selecteer altijd eerst de categorie voordat je andere velden invult !<br />dat is nodig omdat iedere hoofdoudercategorie (en subcategorie�n daarin) andere voorkeuren kunnen hebben.<br /><br /><b>aanmaakformulier</b><br />je kunt nu alle informatie voor dit contentonderwerp invullen en het aanmelden.<br /><br />Let erop dat iedere hoofdoudercategorie andere voorkeuren kan hebben en dat er dus meer of minder velden in te vullen zijn.");

define("CONTENT_ADMIN_HELP_CAT_1", "<i>deze pagina toont alle aanwezige categorie�n en subcategorie�n.</i><br /><br /><b>detail overzicht</b><br />Je ziet een overzicht met alle subcategorie�n met hun id, pictogram, auteur, categorie [onderkop] en opties.<br /><br /><b>Legenda</b><br />".CONTENT_ICON_USER." : link naar auteursprofiel<br />".CONTENT_ICON_LINK." : link naar de categorie<br />".CONTENT_ICON_EDIT." : bewerk categorie<br />".CONTENT_ICON_DELETE." : verwijder categor<br />");

define("CONTENT_ADMIN_HELP_CAT_2", "".CONTENT_ICON_CONTENTMANAGER_SMALL." : (alleen hoofdbeheerder) voor elke subcategorie kun je via de knop het Persoonlijke Beheer voor andere beheerders regelen.<br />
<br /><b>persoonlijke beheerder</b><br />je kunt beheerders toewijzen aan bepaalde categorie�n. Hierdoor kunnen deze beheerders hun persoonlijke content voor deze categorie�n beheren zonder het beheerscherm te moeten gebruiken (content_manager.php).");

define("CONTENT_ADMIN_HELP_CAT_3", "<i>op deze pagina kun je een nieuwe categorie maken</i><br /><br />
KIES ALTIJD EERST DE OUDERCATEGORIE VOORDAT JE DE ANDERE VELDEN INVULT !<br /><br />
Dit moet je doen omdat sommige unieke categorieinformatie eerst in het formulier moet worden geladen.");
define("CONTENT_ADMIN_HELP_CAT_4", "<i>deze pagina toont het formulier om categorie�n te bewerken.</i><br /><br />
<b>bewerk categorie formulier</b><br />je kunt nu alle gegevens voor deze (sub)categorie bewerken en de wijzigingen aanmelden.");

define("CONTENT_ADMIN_HELP_ORDER_1", "<i>deze pagina toont alle aanwezige categorie�n en subcategorie�n.</i><br /><br /><b>detail overzicht</b><br />hier zie je de categorie id en de categorienaam. ook tref je verschillende opties aan om de volgorde van de categorie�n te wijzigen.<br /><br /><b>Legenda</b><br />".CONTENT_ICON_USER." : link naar auteursprofiel<br />".CONTENT_ICON_LINK." : link naar de categorie<br />".CONTENT_ICON_ORDERALL." : beheer algemene volgorde van onderwerpen, ongeacht de categorie.<br />".CONTENT_ICON_ORDERCAT." : beheer algemene volgorde van onderwerpen, binnen deze categorie.<br />".CONTENT_ICON_ORDER_UP." : de omhoogknop verplaatst een onderwerp omhoog.<br />".CONTENT_ICON_ORDER_DOWN." : de omlaagknop verplaatst een onderwerp omlaag.<br /><br /><b>volgorde</b><br />hier kun je handmatig de volgorde van alle categorie�n in elke ouder bepalen. Je moet de waarde in de selectievelden wijzigen in de gewenste waarde en daarna druk je op de Bijwerken knop hieronder om de nieuwe volgorde vast te leggen.<br />");

define("CONTENT_ADMIN_HELP_ORDER_2", "<i>deze pagina toont alle content onderwerpen in de geselecteerde categorie.</i><br /><br /><b>detail overzicht</b><br />je ziet het content id, de content auteur en de content kop. ook staan hier verschillende opties om de volgorde van de content onderwerpen te wijzigen.<br /><br /><b>Legenda</b><br />".CONTENT_ICON_USER." : link naar auteursprofiel<br />".CONTENT_ICON_LINK." : link naar onderwerp<br />".CONTENT_ICON_ORDER_UP." : de omhoogknop verplaatst een onderwerp omhoog.<br />".CONTENT_ICON_ORDER_DOWN." : de omlaagknop verplaatst een onderwerp omlaag.<br /><br /><b>volgorde</b><br />hier kun je handmatig de volgorde van alle categorie�n in elke ouder bepalen. Je moet de waarde in de selectievelden wijzigen in de gewenste waarde en daarna druk je op de Bijwerken knop hieronder om de nieuwe volgorde vast te leggen.<br />");

define("CONTENT_ADMIN_HELP_ORDER_3", "<i>deze pagina toont alle content onderwerpen in de geselecteerde oudercategorie.</i><br /><br /><b>detail overzicht</b><br />je ziet het content id, de content auteur en de content kop. ook staan hier verschillende opties om de volgorde van de content onderwerpen te wijzigen.<br /><br /><b>Legenda</b><br />".CONTENT_ICON_USER." : link naar auteursprofiel<br />".CONTENT_ICON_LINK." : link to the content item<br />".CONTENT_ICON_ORDER_UP." : de omhoogknop verplaatst een onderwerp omhoog.<br />".CONTENT_ICON_ORDER_DOWN." : de omlaagknop verplaatst een onderwerp omlaag.<br /><br /><b>volgorde</b><br />hier kun je handmatig de volgorde van alle categorie�n in elke ouder bepalen. Je moet de waarde in de selectievelden wijzigen in de gewenste waarde en daarna druk je op de Bijwerken knop hieronder om de nieuwe volgorde vast te leggen.<br />");


define("CONTENT_ADMIN_HELP_OPTION_1", "Op deze pagina kun je de opties voor hoofdoudercategorie instellen, of je kunt de standaardvoorkeuren wijzigen.<br /><br /><b>uitleg van de gebruikte pictogrammen</b><br />".CONTENT_ICON_USER." : link naar het auteursprofiel<br />".CONTENT_ICON_LINK." : link naar de categorie<br />".CONTENT_ICON_OPTIONS." : bewerk de opties<br />");

//define("CONTENT_ADMIN_HELP_OPTION_2", "<i>deze pagina toont de opties die je voor de hoofdouder kunt instellen. Elke hoofdouder heeft een eigen set opties, let er dus op de juiste gegevens goed in te vullen.</i><br /><br />");
//<b>standaardwaarden</b><br />Standaard zijn alle waarden al aanwezig en bijgewerkt in de voorkeuren als je deze pagina bekijkt, je kunt alles naar willekeur aanpassen.<br /><br />

define("CONTENT_ADMIN_HELP_MANAGER_1", "Op deze pagina zie je het overzicht van alle categorie�n. Je kunt de 'persoonlijke content beheerder' voor elke categorie beheren door op het pictogram te kliken.<br /><br /><b>uitleg van de gebruikte pictogrammen</b><br />".CONTENT_ICON_USER." : link naar het auteursprofiel<br />".CONTENT_ICON_LINK." : link naar de categorie<br />".CONTENT_ICON_CONTENTMANAGER_SMALL." : bewerk de persoonlijke content beheerders<br />");

define("CONTENT_ADMIN_HELP_MANAGER_2", "<i>op deze pagina kun je gebruikers toewijzen aan de geselecteerde categorie</i><br /><br /><b>persoonlijke beheerder</b><br />je kunt gebruikers aan bepaalde categorie�n toewijzen. Hierdoor kunnen die gebruikers hun persoonlijke contentonderwerpen binnen deze categorie beheren buiten het beheerscherm om (content_manager.php).<br /><br />Selecteer de gebruikers uit de linkerkolom. Door op een naam te klikken wordt de gebruiker verplaatst naar de rechterkolom. Nadat je op Toewijzen hebt gedrukt, worden de gebruikers in de rechterkolom toegewezen aan deze categorie.");

define("CONTENT_ADMIN_HELP_SUBMIT_1", "<i>Op deze pagina zie je een overzicht van alle door gebruikers aangemelde contentonderwerpen.</i><br /><br />
<b>detail overzicht</b><br />Je ziet hier een overzicht van deze content onderwerpen met hun id, pictogram, hoofd ouder, kop [onderkop], auteur en opties.<br /><br /><b>opties</b><br />je kunt een content onderwerp met behulp van de knoppen plaatsen of verwijderen.");

define("CONTENT_ADMIN_HELP_CAT_5", "");

define("CONTENT_ADMIN_HELP_CAT_6", "<i>deze pagina toont de opties die je voor de hoofdouder in kunt stellen. Iedere hoofdouder heeft een eigen set specifieke opties, let er dus op om ze allemaal goed in te stellen.</i><br /><br />
<b>standaardwaarden</b><br />Standaard zijn alle waarden al aanwezig en bijgewerkt in de voorkeuren als je deze pagina bekijkt, je kunt alles naar willekeur aanpassen.<br /><br />
<b>verdeling in acht secties</b><br />de opties zijn in acht secties verdeeld. Je ziet de verschillende secties in het rechtermenu. Klik op een sectie om de opties voor die sectie te zien.<br /><br />
<b>cre�ren</b><br />in deze sectie kun je de opties opgeven voor het cre�ren van contentonderwerpen op de beheerpagina in het beheerscherm.<br /><br />
<b>aanmelden</b><br />in deze sectie kun je de opties voor het contentonderwerp aanmeldformulier opgeven.<br /><br />
<b>pad en thema</b><br />in deze sectie kun je het thema voor deze hoofdouder instellen en de paden opgeven waar de afbeeldingen voor deze hoofdouder te vinden zjin.<br /><br />
<b>algemeen</b><br />in deze sectie kun je de algemene opties instellen die voor alle contentpagina's gelden.<br /><br />
<b>overzicht pagina's</b><br />in deze sectie kun je de optiepagina's instellen voor de overzichtspagina's.<br /><br />
<b>categoriepagina's</b><br />in deze sectie kun je opgeven hoe de categorie pagina's moeten worden weergegeven.<br /><br />
<b>content pagina's</b><br />in deze sectie kun je opgeven hoe een contentonderwerp pagina moet worden weergegeven.<br /><br />
<b>menu</b><br />in deze sectie kun je de opties instellen voor het menu van de hoofdouder.<br /><br />
");

define("CONTENT_ADMIN_HELP_CAT_7", "<i>op deze pagina kun je beheerders toewijzen aan de geselecteerde categorie</i><br /><br />
Wijs de beheerders toe vanuit de linkerkolom door op de naam te klikken. de naam wordt verplaatst naar de rechterkolom. na het klikken op de toewijsknop worden de beheerders in de rechterkolom toegewezen aan deze catagorie.");

define("CONTENT_ADMIN_HELP_ITEM_LETTERS", "Hieronder zie je een lijst met de beginletters van de koppen van onderwerpen in deze categorie.<br />Door op een letter te klikken zie je een overzicht met alle onderwerpen die met die letter beginnen. Je kunt ook de ALLES knop indrukken om alle onderwerpen in deze categorie te laten tonen.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_1", "Op deze pagina kun je de opties voor de Beheerderspagina voor het cre�ren van content onderwerpen instellen .<br /><br />Je kunt bepalen welke secties beschikbaar zijn als een beheerder (of een persoonlijke contentbeheerder) een nieuw contentonderwerp aanmaakt.<br /><br /><b>Maatwerk data tags</b><br />Je kunt een gebruiker of beheerder toestaan om extra velden aan het content onderwerp toe te voegen door gebruik te maken van maatwerk data tags. Deze optionele velden zijn blanco Sleutel - > Waarde paren. Voorbeeld: je kunt een sleutelveld toevoegen voor 'fotograaf' en de waarde in het veld vullen met 'alle foto's door mij'. Zowel deze sleutel als waarde velden zijn blanco tekstvelden die in het Cre�er Formulier beschikbaar zijn.<br /><br /><b>Vooringestelde data tags</b><br />naast de maatwerk data tags, kun je ook vooringestelde data tags aanbieden. Het verschil is dat in de vooringestelde data tags het sleutelveld al is ingevuld en dat de gebruiker dus alleen nog de waarde hoeft op te geven. In dit voorbeeld kan 'fotograaf' voorgedefinieerd zijn en moet de gebruiker zelf 'alle foto's door mij' invullen. Je kunt het element type kiezen door een optie uit het selectieveld te kiezen. In het popup venster kun je vervolgens alle informatie voor de vooringestelde data tag opgeven.<br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_2", "De Bijwerk opties werken op het Gebruikers aanmeldformulier voor content onderwerpen.<br /><br />Je kunt bepalen welke secties voor een gebruiker beschikbaar zijn bij het aanmelden van een content onderwerp.<br /><br />".CONTENT_ADMIN_OPT_LAN_11.":<br />".CONTENT_ADMIN_OPT_LAN_12."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_3", "In de Pad en thema Opties kun je opgeven waar de afbeeldingen en bestanden worden opgeslagen.<br /><br />Je kunt opgeven welk thema wordt gebruikt door deze hoofdouder. Je kunt extra thema's maken door het kopi�ren (en hernoemen) van de hele 'default' map in je 'templates' map.<br /><br />Je kunt een standaard layout schema voor nieuwe onderwerpen instellen. Je kunt ook nieuwe layout schema's maken door een content_content_template_XXX.php bestand in je 'templates/default' map op te nemen. Deze layouts kunnen worden gebruikt om elk contentonderwerp een andere layout te geven in dit hoofdouderscherm.<br /><br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_4", "Algemene opties zijn opties die op alle contentpagina's in de content manager plugin worden toegepast.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_5", "Deze opties werken binnen de Persoonlijke Content Manager in de content management beheerpagina.<br /><br />".CONTENT_ADMIN_OPT_LAN_63."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_6", "'Deze opties gelden in het menu voor deze hoofdouder als je het menu hebt geactiveerd.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."<br /><br />".CONTENT_ADMIN_OPT_LAN_118.":<br />".CONTENT_ADMIN_OPT_LAN_119."<br /><br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_7", "De Contentonderwerp Bekijk opties werken op het kleine voorbeschouwingsscherm voor het betreffende onderwerp.<br /><br />Deze preview zie je op verschillende pagina's, zoals het Recent overzicht, de Onderwerpen per categorie pagina en de Onderwerpen per auteur pagina.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_8", "De categoriepagina's tonen infomratie over de contentcategorie�n in deze hoofdouder.<br /><br />Er zijn twee specifieke onderdelen zichtbaar:<br /><br />alle categorie�n pagina:<br />deze pagina toont alle categorie�n in deze hoofdouder<br /><br />bekijk categorie pagina:<br />deze pagina toont alle categorieonderwerpen en, optioneel, de subcategorie�n binnen die categorie en de contentonderwerpen in die categorie of in die subcategorie�n<br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_9", "De Content Pagina toont het contentonderwerp.<br /><br />Je kunt instellen welke secties getoond moeten worden door het aan- en uitkruisen van de vakjes.<br /><br />Je kunt het e-mailadres van een auteur/niet-lid laten zien.<br /><br />Je kunt de e-mail/print/pdf pictogrammen negeren, evenals het waarderingssysteem en de reactiefunctie.<br /><br />".CONTENT_ADMIN_OPT_LAN_74."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_10", "De auteurspagina toont een overzicht met alle unieke auteurs van onderwerpen in deze hoofdouder.<br /><br />Je kunt instellen welke secties getoond moeten worden door het aan/uitkruisen van de vakjes.<br /><br />Je kunt het aantal per pagina te tonen onderwerpen beperken.<br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_11", "De Archiefpagina toont alle contentonderwerpen in de hoofdouder.<br /><br />Je kunt instellen welke secties getoond moeten worden door het aan- en uitkruisen van de vakjes.<br /><br />Je kunt het e-mailadres van een auteur/niet-lid laten zien.<br /><br />Je kunt het aantal per pagina te tonen onderwerpen beperken.<br /><br />".CONTENT_ADMIN_OPT_LAN_66."<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_12", "De pagina Hoogstgewaardeerd toont alle contentonderwerpen die door gebruikers zijn beoordeeld.<br /><br />Je kunt instellen welke secties getoond moeten worden door het aan- en uitkruisen van de vakjes.<br /><br />Je kunt het e-mailadres van een auteur/niet-lid laten zien.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_13", "De Topscore pagina toont alle contentonderwerpen die door de auteur zijn beoordeeld.<br /><br />Je kunt instellen welke secties getoond moeten worden door het aan- en uitkruisen van de vakjes.<br /><br />Je kunt het e-mailadres van een auteur/niet-lid laten zien.");


?>