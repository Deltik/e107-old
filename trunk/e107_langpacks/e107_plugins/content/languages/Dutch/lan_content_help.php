<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Dutch/lan_content_help.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-06-21 20:45:32 $
|     $Author: mijnheer $
+----------------------------------------------------------------------------+
*/

define("CONTENT_ADMIN_HELP_1", "Contentbeheer hulp");

define("CONTENT_ADMIN_HELP_ITEM_1", "<i>als je nog geen hoofdoudercategorieën hebt aangemaakt, doe dat dan nu op de <a href='".e_SELF."?type.0.cat.create'>Creëren nieuwe categorie</a> pagina.</i><br /><br />
<b>categorie</b><br />selecteer een categorie uit het uitklapmenu om de content voor die categorie te beheren.<br /><br />
de hoofdouders zijn vet weergegeven en hebben de (ALLE) extensie. het kiezen hiervan toont alle onderwerpen bij deze hoofdouder.<br /><br />
voor iedere hoofdouder worden alle subcategorieën getoond, inclusief de hoofdoudercategorie zelf (deze worden in normale tekstopmaak getoond). Het kiezen van een van deze categorieën toont alleen de onderwerpen uit de betreffende categorie.");

define("CONTENT_ADMIN_HELP_ITEM_2", "<b>eerste letters</b><br />als er meerdere content onderwerpen beginnen met dezelfe letter in de kop, dan zie je de beginletters staan , waardoor je de onderwerpen kunt selecteren die met die letter beginnen. Het drukken op de 'alle' knop toont alle onderwerpen in deze categorie.<br /><br />
<b>detail overzicht</b><br />Je ziet het overzicht van alle onderwerpen met hun id, pictogram, auteur, kop [onderkop] en opties.<br /><br />
<b>uitleg van de gebruikte pictogrammen</b><br />
".CONTENT_ICON_EDIT." : bewerk het contentonderwerp.<br />".CONTENT_ICON_DELETE." : verwijder het contentonderwerp.<br />");

define("CONTENT_ADMIN_HELP_ITEMEDIT_1", "<b>bewerk formulier</b><br />je kunt nu alle informatie voor dit contentonderwerp invullen en je wijzigingen aanmelden.<br /><br />
Als je de categorie van dit contentonderwerp verandert in een andere hoofdoudercategorie, zul je dit onderwerp waarschjinlijk willen bewerken na de categoriewijziging.<br />Als je een categorie wijzigt, kunnen er andere instellingen gelden waardoor je meer of minder velden in kunt vullen.");

define("CONTENT_ADMIN_HELP_ITEMCREATE_1", "<b>categorie</b><br />selecteer de categorie uit de keuzelijst waarvoor je een contentonderwerp wilt aanmaken.<br />");

define("CONTENT_ADMIN_HELP_ITEMCREATE_2", "selecteer altijd eerst de categorie voordat je andere velden invult !<br />
dat is nodig omdat iedere hoofdoudercategorie (en subcategorieën daarin) andere voorkeuren kunnen hebben.<br /><br />
<b>aanmaakformulier</b><br />je kunt nu alle informatie voor dit contentonderwerp invullen en het aanmelden.<br /><br />
Let erop dat iedere hoofdoudercategorie andere voorkeuren kan hebben en dat er dus meer of minder velden in te vullen zijn.");

define("CONTENT_ADMIN_HELP_CAT_1", "<i>deze pagina toont alle aanwezige categorieën en subcategorieën.</i><br /><br />
<b>detail overzicht</b><br />Je ziet een overzicht met alle subcategorieën met hun id, pictogram, auteur, categorie [onderkop] en opties.<br >
<br />
<b>uitleg over de gebruikte pictogrammen</b><br />
".CONTENT_ICON_EDIT." : voor alle categorieën kun je deze knop gebruiken om de categorie wilt bewerken.<br />
".CONTENT_ICON_DELETE." : voor alle categorieën kun je deze knop gebruiken om de categorie wilt verwijderen.<br />
".CONTENT_ICON_OPTIONS." : alleen voor de hoofdcategorie (bovenan de lijst) kun je de knop gebruiken om alle opties in te stellen en beheren.<br />");

define("CONTENT_ADMIN_HELP_CAT_2", "".CONTENT_ICON_CONTENTMANAGER_SMALL." : (alleen hoofdbeheerder) voor elke subcategorie kun je via de knop het Persoonlijke Beheer voor andere beheerders regelen.<br />
<br /><b>persoonlijke beheerder</b><br />je kunt beheerders toewijzen aan bepaalde categorieën. Hierdoor kunnen deze beheerders hun persoonlijke content voor deze categorieën beheren zonder het beheerscherm te moeten gebruiken (content_manager.php).");

define("CONTENT_ADMIN_HELP_CAT_3", "<i>op deze pagina kun je een nieuwe categorie maken</i><br /><br />
KIES ALTIJD EERST DE OUDERCATEGORIE VOORDAT JE DE ANDERE VELDEN INVULT !<br /><br />
Dit moet je doen omdat sommige unieke categorieinformatie eerst in het formulier moet worden geladen.");
define("CONTENT_ADMIN_HELP_CAT_4", "<i>deze pagina toont het formulier om categorieën te bewerken.</i><br /><br />
<b>bewerk categorie formulier</b><br />je kunt nu alle gegevens voor deze (sub)categorie bewerken en de wijzigingen aanmelden.");

define("CONTENT_ADMIN_HELP_ORDER_1", "
<i>deze pagina toont alle aanwezige categorieën en subcategorieën.</i><br /><br />
<b>detail overzicht</b><br />hier zie je de categorie id en de categorienaam. ook tref je verschillende opties aan om de volgorde van de categorieën te wijzigen.<br /><br />
<b>uitleg van de gebruikte pictogrammen</b><br />
".CONTENT_ICON_ORDERALL." beheer de algemene volgorde van de contentonderwerpen, ongeacht hun categorie.<br />
".CONTENT_ICON_ORDERCAT." beheer de volgorde van de content onderwerpen in de betreffende categorie.<br />
<img src='".e_IMAGE."admin_images/up.png' alt='' /> de omhoog knop laat je een content onderwerp één positie omhoog verplaatsen.<br />
<img src='".e_IMAGE."admin_images/down.png' alt='' /> de omlaag knop laat je een content onderwerp één positie omlaag plaatsen.<br />
<br />
<b>volgorde</b><br />hier kun je handmatig de volgorde van alle categorieën in elke ouder bepalen. Je moet de waarde in de selectievelden wijzigen in de gewenste waarde en daarna druk je op de Bijwerken knop hieronder om de nieuwe volgorde vast te leggen.<br />");

define("CONTENT_ADMIN_HELP_ORDER_2", "<i>deze pagina toont alle content onderwerpen in de geselecteerde categorie.</i><br /><br />
<b>detail overzicht</b><br />je ziet het content id, de content auteur en de content kop. ook staan hier verschillende opties om de volgorde van de content onderwerpen te wijzigen.<br />
<br /><b>uitleg over de gebruikte pictogrammen</b><br />
<img src='".e_IMAGE."admin_images/up.png' alt='' /> de omhoog knop laat je een content onderwerp één positie omhoog verplaatsen.<br />
<img src='".e_IMAGE."admin_images/down.png' alt='' /> de omlaag knop laat je een content onderwerp één positie omlaag plaatsen.<br />
<br /><b>volgorde</b><br />hier kun je handmatig de volgorde van alle categorieën in elke ouder bepalen. Je moet de waarde in de selectievelden wijzigen in de gewenste waarde en daarna druk je op de Bijwerken knop hieronder om de nieuwe volgorde vast te leggen.<br />");

define("CONTENT_ADMIN_HELP_ORDER_3", "
<i>deze pagina toont alle content onderwerpen in de geselecteerde oudercategorie.</i><br /><br />
<b>detail overzicht</b><br />je ziet het content id, de content auteur en de content kop. ook staan hier verschillende opties om de volgorde van de content onderwerpen te wijzigen.<br />
<br /><b>uitleg over de gebruikte pictogrammen</b><br />
<img src='".e_IMAGE."admin_images/up.png' alt='' /> de omhoog knop laat je een content onderwerp één positie omhoog verplaatsen.<br />
<img src='".e_IMAGE."admin_images/down.png' alt='' /> de omlaag knop laat je een content onderwerp één positie omlaag verplaatsen.<br />
<br /><b>volgorde</b><br />hier kun je handmatig de volgorde van alle categorieën in elke ouder bepalen. Je moet de waarde in de selectievelden wijzigen in de gewenste waarde en daarna druk je op de Bijwerken knop hieronder om de nieuwe volgorde vast te leggen.<br />");

define("CONTENT_ADMIN_HELP_OPTION_1", "Op deze pagina kun je de opties voor hoofdoudercategorie instellen, of je kunt de standaardvoorkeuren wijzigen.<br /><br /><b>uitleg van de gebruikte pictogrammen</b><br />".CONTENT_ICON_USER." : link naar het auteursprofiel<br />".CONTENT_ICON_LINK." : link naar de categorie<br />".CONTENT_ICON_OPTIONS." : bewerk de opties<br />");

define("CONTENT_ADMIN_HELP_OPTION_2", "
<i>deze pagina toont de opties die je voor de hoofdouder kunt instellen. Elke hoofdouder heeft een eigen set opties, let er dus op de juiste gegevens goed in te vullen.</i><br /><br />
<b>standaardwaarden</b><br />Standaard zijn alle waarden al aanwezig en bijgewerkt in de voorkeuren als je deze pagina bekijkt, je kunt alles naar willekeur aanpassen.<br /><br />
");

define("CONTENT_ADMIN_HELP_MANAGER_1", "Op deze pagina zie je het overzicht van alle categorieën. Je kunt de 'persoonlijke content beheerder' voor elke categorie beheren door op het pictogram te kliken.<br /><br /><b>uitleg van de gebruikte pictogrammen</b><br />".CONTENT_ICON_USER." : link naar het auteursprofiel<br />".CONTENT_ICON_LINK." : link naar de categorie<br />".CONTENT_ICON_CONTENTMANAGER_SMALL." : bewerk de persoonlijke content beheerders<br />");

define("CONTENT_ADMIN_HELP_MANAGER_2", "<i>op deze pagina kun je gebruikers toewijzen aan de geselecteerde categorie</i><br /><br /><b>persoonlijke beheerder</b><br />je kunt gebruikers aan bepaalde categorieën toewijzen. Hierdoor kunnen die gebruikers hun persoonlijke contentonderwerpen binnen deze categorie beheren buiten het beheerscherm om (content_manager.php).<br /><br />Selecteer de gebruikers uit de linkerkolom. Door op een naam te klikken wordt de gebruiker verplaatst naar de rechterkolom. Nadat ke op Toewijzen hebt gedrukt, worden de gebruikers in de rechterkolom toegewezen aan deze categorie.");

define("CONTENT_ADMIN_HELP_SUBMIT_1", "<i>Op deze pagina zie je een overzicht van alle door gebruikers aangemelde contentonderwerpen.</i><br /><br />
<b>detail overzicht</b><br />Je ziet hier een overzicht van deze content onderwerpen met hun id, pictogram, hoofd ouder, kop [onderkop], auteur en opties.<br /><br /><b>opties</b><br />je kunt een content onderwerp met behulp van de knoppen plaatsen of verwijderen.");



define("CONTENT_ADMIN_HELP_CAT_5", "");

define("CONTENT_ADMIN_HELP_CAT_6", "<i>deze pagina toont de opties die je voor de hoofdouder in kunt stellen. Iedere hoofdouder heeft een eigen set specifieke opties, let er dus op om ze allemaal goed in te stellen.</i><br /><br />
<b>standaardwaarden</b><br />Standaard zijn alle waarden al aanwezig en bijgewerkt in de voorkeuren als je deze pagina bekijkt, je kunt alles naar willekeur aanpassen.<br /><br />
<b>verdeling in acht secties</b><br />de opties zijn in acht secties verdeeld. Je ziet de verschillende secties in het rechtermenu. Klik op een sectie om de opties voor die sectie te zien.<br /><br />
<b>creëren</b><br />in deze sectie kun je de opties opgeven voor het creëren van contentonderwerpen op de beheerpagina in het beheerscherm.<br /><br />
<b>aanmelden</b><br />in deze sectie kun je de opties voor het contentonderwerp aanmeldformulier opgeven.<br /><br />
<b>pad en thema</b><br />in deze sectie kun je het thema voor deze hoofdouder instellen en de paden opgeven waar de afbeeldingen voor deze hoofdouder te vinden zjin.<br /><br />
<b>algemeen</b><br />in deze sectie kun je de algemene opties instellen die voor alle contentpagina's gelden.<br /><br />
<b>overzicht pagina's</b><br />in deze sectie kun je de optiepagina's instellen voor de overzichtspagina's.<br /><br />
<b>categoriepagina's</b><br />in deze sectie kun je opgeven hoe de categorie pagina's moeten worden weergegeven.<br /><br />
<b>content pagina's</b><br />in deze sectie kun je opgeven hoe een contentonderwerp pagina moet worden weergegeven.<br /><br />
<b>menu</b><br />in deze sectie kun je de opties instellen voor het menu van de hoofdouder.<br /><br />
");

define("CONTENT_ADMIN_HELP_CAT_7", "<i>op deze pagina kun je beheerders toewijzen aan de geselecteerde catagorie</i><br /><br />
Wijs de beheerders toe vanuit de linkerkolom door op de naam te klikken. de naam wordt verplaatst naar de rechterkolom. na het klikken op de toewijsknop worden de beheerders in de rechterkolom toegewezen aan deze catagorie.");

define("CONTENT_ADMIN_HELP_ITEM_LETTERS", "Hieronder zie je een lijst met de beginletters van de koppen van onderwerpen in deze categorie.<br />Door op een letter te klikken zie je een overzicht met allen onderwerpen die met die letter beginnen. Je kunt ook de ALLES knop indrukken om alle onderwerpen in deze categorie te laten tonen.");


?>