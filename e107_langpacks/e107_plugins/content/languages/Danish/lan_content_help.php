<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Danish/lan_content_help.php,v $
|     $Revision: 1.2 $
|     $Date: 2005-07-09 09:59:15 $
|     $Author: e107dk $
+----------------------------------------------------------------------------+
*/

define("CONTENT_ADMIN_HELP_1", "Indholds H�ndtering Hj�lp Omr�de");

define("CONTENT_ADMIN_HELP_ITEM_1", "<i>hvis du endnu ikke har opretet hoved for�lder kategorier, s� g�r det p� <a href='".e_SELF."?cat.create'>Opret Ny Kategori</a> siden.</i><br /><br /><b>kategori</b><br />v�lg en kategori fra rullemenuen for at h�ndtere indhold for den kategori.<br /><br />Ved at v�lge en hoved for�lder fra rullemenuen vises alle indholds emner i den hoved kategori.<br />Ved at v�lge en under kategori vises kun indholdet i den valgte underkategori.<br /><br />Du kan ogs� bruge menuen til h�jre til at vise alle indholds emner for en bestemt kategori.");

define("CONTENT_ADMIN_HELP_ITEM_2", "<b>f�rste bogstaver</b><br />hvis flere indholds emner starter med bogstaver af content_heading er til stede, vil du se knapper til at v�lge kun de indholds emner startende med det bogstav. Ved valg af 'alle' knappen vises en liste indeholdende alle indholds emner i den kategori.<br /><br /><b>detalje liste</b><br />Du ser en liste af alle indholds emner med deres id, ikon, forfatter, overskrift [under overskrift] og indstillinger.<br /><br /><b>forklaring af ikoner</b><br />".CONTENT_ICON_USER." : link til forfatter profil<br />".CONTENT_ICON_LINK." : link til indholds emnet<br />".CONTENT_ICON_EDIT." : rediger indholds emnet<br />".CONTENT_ICON_DELETE." : slet indholds emnet<br />");

define("CONTENT_ADMIN_HELP_ITEMEDIT_1", "<b>rediger formular</b><br />du kan nu redigere alle informationerne for dette indholds emne og tilf�je dine �ndringer.<br /><br />Hvis du beh�ver at �ndre kategorien for dette indholds emne, s� g�r venligst dette f�rst. Efter du har valgt den korrekte kategori, �ndrer eller opret evt. felter der er tilstede, f�r du kan tilf�je �ndringerne.");

define("CONTENT_ADMIN_HELP_ITEMCREATE_1", "<b>kategori</b><br />v�lg venligst en kategori fra boksen for at oprette dit indholds emne for.<br />");

define("CONTENT_ADMIN_HELP_ITEMCREATE_2", "<b>oprettelses formular</b><br />du kan levere alle informationer til dette indholds emne og oprette det.<br /><br />V�r opm�rksom p� at forskellige hoved for�lder kategorier kan have hver deres s�t indstillinger; forskellige felter kan v�re tilg�ngelige for dig at udfylde. Derfor er du altid n�d til at v�lge en kategori f�r du udfylder andre felter !");

define("CONTENT_ADMIN_HELP_CAT_1", "<i>denne side viser alle kategorier og underkategorier der er tilstede.</i><br /><br /><b>detaljret liste</b><br />Du ser en liste over alle underkategorier med deres id, ikon, forfatter, kategori [underoverskrift] og egenskaber.<br /><br /><b>forklaring af ikoner</b><br />".CONTENT_ICON_USER." : link til forfatterens profil<br />".CONTENT_ICON_LINK." : link til kategorien<br />".CONTENT_ICON_EDIT." : rediger kategorien<br />".CONTENT_ICON_DELETE." : slet kategorien<br />");

define("CONTENT_ADMIN_HELP_CAT_2", "<i>denne side tillader at oprette en ny kategori</i><br /><br />V�lg altid en for�lder kategori f�r du udfylder de andre felter !<br /><br />Dette skal g�res, fordi nogle unikke kategori egenskaber skal indl�ses i systemet.<br /><br />Som standard vises kategori siden for at oprette en ny hovedkategori.");

define("CONTENT_ADMIN_HELP_CAT_3", "<i>denne side viser rediger kategori formularen.</i><br /><br /><b>rediger kategori formular</b><br />du kan nu redigere alle informationer for denne (under)kategori og tilf�je dine �ndringer.<br /><br />Hvis du �nsker at �ndre for�lder beliggenhed for denne kategori, s� g�r venligst dette f�rst. Efter du har indstillet den kategori rediger alle andre felter.");

define("CONTENT_ADMIN_HELP_ORDER_1", "<i>denne side viser alle kategorier og underkategorier der er tilstede.</i><br /><br /><b>detaljeret liste</b><br />du ser kategori id og kategori navn. du ser ogs� adskillige muligheder til at h�ndtere sorteringen af kategorierne.<br /><br /><b>forklaring af ikoner</b><br />".CONTENT_ICON_USER." : link til forfatter profilen<br />".CONTENT_ICON_LINK." : link til kategorien<br />".CONTENT_ICON_ORDERALL." : h�ndter den generelle sortering af indhold uanset kategori.<br />".CONTENT_ICON_ORDERCAT." : h�ndter sorteringen af indholds emner i den enkelte kategori.<br />".CONTENT_ICON_ORDER_UP." : op knappen tillader dig at flytte et indholds emne en plads op i ordnen.<br />".CONTENT_ICON_ORDER_DOWN." : ned knappen lader dig at flytte et indholds emne en plads ned i ordnen.<br /><br /><b>sortering</b><br />her kan du manuelt indstille ordnen af alle kategorier i denne for�lder. Du skal �ndre v�rdierne i boksene for den orden du �nsker og s� trykke p� opdater knappen nedenfor for at gemme den nye sortering.<br />");

define("CONTENT_ADMIN_HELP_ORDER_2", "<i>denne side viser alle indholds emner fra den kategori du har valgt.</i><br /><br /><b>detaljeret liste</b><br />du ser indholds id, indholds forfatter og indholds overskrift. du ser ogs� adskillige muligheder til at h�ndtere sorteringen af indholds emnerne.<br /><br /><b>forklaring af ikoner</b><br />".CONTENT_ICON_USER." : link til forfatter profilen<br />".CONTENT_ICON_LINK." : link til indholds emne<br />".CONTENT_ICON_ORDER_UP." : op knappen lader dig flytte et indholds emne en plads op i ordnen.<br />".CONTENT_ICON_ORDER_DOWN." : ned knappen lader dig flytte et indholds emne en plads ned i ordnen.<br /><br /><b>sortering</b><br />her kan du manuelt indstille ordnen af alle kategorier i denne for�lder. Du skal �ndre v�rdierne i boksene for den orden du �nsker og s� trykke p� opdater knappen nedenfor for at gemme den nye sortering.<br />");

define("CONTENT_ADMIN_HELP_ORDER_3", "<i>denne side viser alle indholds emner fra den hoved kategori for�lder du har valgt.</i><br /><br /><b>detaljeret liste</b><br />du ser indholds id, indholds forfatter og indholds overskrift. du ser ogs� adskillige muligheder til at h�ndtere sorteringen af indholds emnerne.<br /><br /><b>forklaring af ikoner</b><br />".CONTENT_ICON_USER." : link til forfatter profilen<br />".CONTENT_ICON_LINK." : link til indholds emne<br />".CONTENT_ICON_ORDER_UP." : op knappen lader dig flytte et indholds emne en plads op i ordnen.<br />".CONTENT_ICON_ORDER_DOWN." : ned knappen lader dig flytte et indholds emne en plads ned i ordnen.<br /><br /><b>sortering</b><br />her kan du manuelt indstille ordnen af alle kategorier i hver for�lder. Du skal �ndre v�rdierne i boksene for den orden du �nsker og s� trykke p� opdater knappen nedenfor for at gemme den nye sortering.<br />");

define("CONTENT_ADMIN_HELP_OPTION_1", "P� denne side kan du v�lge en hoved kategori for�lder til at indstille egenskaber for, eller du kan v�lge at redigere standard indstillingerne.<br /><br /><b>forklaring af ikoner</b><br />".CONTENT_ICON_USER." : link til forfatter profilen<br />".CONTENT_ICON_LINK." : link til kategorien<br />".CONTENT_ICON_OPTIONS." : rediger egenskaber<br /><br /><br />
Standard indstillingerne bruges kun n�r du opretter en ny hoved for�lder. S� n�r du opretter en hoved for�lder vil disse standard indstillinger blive gemt. Du kan �ndre disse for at sikre at nyligt oprettede hoved for�ldre allerede har et bestemt s�t muligheder tilstede.
<br /><br />
Hver hoved for�lder har sit eget s�t indstillinger, der er unikke til den bestemte hoved kategori for�lder");

?>