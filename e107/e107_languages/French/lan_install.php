<?php

/*
French Translation File
Author: marj
Email: mar.j@free.fr
*/

define("INSLAN1", "Phase d\'Installation");
define("INSLAN2", "PHP / mySQL v�rification de version / v�rification des permissions fichiers");
define("INSLAN3", "Version PHP");
define("INSLAN4", "�chou�");
define("INSLAN5", "<b>Votre version PHP n\'est pas compatible avec e107<br />(e107 n�cessite au moins la version 4.1.0).</b><br /><br />Si vous utilisez un serveur local sur votre ordinateur vous devrez mettre � jour votre<br />version de PHP pour continuer. Voyez <a href='http://php.net'>php.net</a> pour les instructions. Si vous tentez <br />d\'install e107 sur serveur distant vous devrez contacter les<br />administrateurs et leur demander de mettre PHP � jour pour vous.<br />Relancez ce script une fois la mise � jour effectu�e.");
define("INSLAN6", "Script arr�t�.");
define("INSLAN7", "Pass�");
define("INSLAN8", "Version mySQL");
define("INSLAN9", "<b>e107 n\'a pas pu d�terminer le num�ro de votre version mySQL.</b><br /><br /> Ceci peut signifier que mySQL n\'est pas install� ou non en cours d\'ex�cution, ou<br />vous utulisez peut �tre une version dont le num�ro n\'est pas correctement restitu�<br />(v5.x sont connues pour avoir ce genre de probl�me). Si la prochaine �tape de l\'installation<br />�choue vous devrez v�rifier les status mySQL.");
define("INSLAN10", "Permissions Fichiers");
define("INSLAN11", "n\'est pas accesible en �criture");
define("INSLAN12", "le r�pertoire n\'est pas accesible en �criture");
define("INSLAN13", "Assurez vous que les permissions pour  les fichiers ci-dessus sont positionn�es<br />correctemment sur votre serveur, les permissions doivent �tre misent � 777. Pour positionner les<br />permissions, cliquez le bouton droit sur le fichier situ� dans votre client FTP et choisissez Chmod ou<br />Set File Permissions, puis entrez 777, si la boite de dialogue comporte des cases � cocher<br />alors cochez toutes les cases.<br /><br />Retestez apr�s la configuration des permissions fichiers.");
define("INSLAN14", "Installation de e107");
define("INSLAN15", "ERREUR FATALE");
define("INSLAN16", "Bien quil ne soit pas possible de d�terminer les status de votre mySQL,<br />continuez � l\'�tape suivante.");
define("INSLAN17", "Continuer");
define("INSLAN18", "Retester les Permissions Fichiers");
define("INSLAN19", "Tous les tests serveurs ont aboutis avec succ�s, cliquez sur le bouton afin de proc�der � la phase suivante");
define("INSLAN20", "information mySQL");
define("INSLAN21", "Entrez vos param�tres mySQL ici.<br />Si vous avez les permissions root vous pouvez cr�er une nouvelle base de donn�es en cochant la case, sinon vous devez<br />cr�er une base de donn�es ou en utliser une pr�-existante. <br />Si vous n\'avez qu\'une seule base de donn�es utilis� un pr�fixe afin que les autres scripts partagent la m�me base.<br />Si vous ne connaissez pas les d�tails de votre mySQL contactez votre fournisseur d\'acc�s.");
define("INSLAN22", "Serveur mySQL");
define("INSLAN23", "Nom Utilisateur mySQL");
define("INSLAN24", "Mot de Passe mySQL");
define("INSLAN25", "Base de Donn�es mySQL");
define("INSLAN26", "Pr�fixe de Table");
define("INSLAN27", "Erreur");
define("INSLAN28", "Erreur rencontr�e");
define("INSLAN29", "Vous avez laissez des champs n�cessaires en blanc. R�-entrez les informations mySQL");
define("INSLAN30", "e107 n\'a pas pus �tablir la connection avec mySQL en utilisant les informations que vous avez fourni.<br />Retournez � la derni�re page et assurez vous queles informations sont correctes.");
define("INSLAN31", "v�rification de mySQL");
define("INSLAN32", "Connection � mySQL �tabliee et v�rifi�e.");
define("INSLAN33", "Cr�ation de la base de donn�e");
define("INSLAN34", "Impossible de cr�er la base de donn�e, assurez vous que vous avez les permissions de cr�ation de bases sur votre serveur.");
define("INSLAN35", "La base de donn�e � �t� cr��e avec succ�s.");
define("INSLAN36", "Cliquer sur le bouton afin de proc�der � la phase suivante.");


define("INSLAN37", "Retour � la derni�re page");
define("INSLAN38", "Informations Administrateur");
define("INSLAN39", "Entrez le Nom utilisateur, le Mot de passe et l\'email devotre Administrateur principal ici.<br />Ces d�tails seront utilis�s pour acc�der � l\'espace d\'administration de votre site web.<br />Assurez vous de conserver une trace du Nom utilisateur et du Mot de passe dans un endroit s�r<br />car si vous les oubli� ils ne  pourront pas �tre retrouv�s.");
define("INSLAN40", "Nom Admin");
define("INSLAN41", "Mot de passe Admin");
define("INSLAN42", "Confirmez le Mot de passe");
define("INSLAN43", "Addresse Email Admin");
define("INSLAN44", "Vous avez laiss� des champs vides. R�-entrez les informations admin SVP.");
define("INSLAN45", "Les deux mots de passes ne  correspondent pas. Veuillez les r�-entrer.");
define("INSLAN46", "semble ne pas �tre une adresse valide. Veuillez la r�-entrer.");
define("INSLAN47", "Tout est param�tr�!");
define("INSLAN48", "e107 a maintenat toute les informations n�cessaires pour finir l\'installation.<br />Cliquer sur le bouton pour cr�er les tables de la base de donn�es et enregistrer vos param�tres.");
define("INSLAN49", "e107 n\'a pas pu sauvegard� le fichier de configuration principale sur votre serveur<br />Assurez vous que le fichier <b>e107_config.php</b> poss�de les permissions correctes");
define("INSLAN50", "Installation Compl�te!");
define("INSLAN51", "Termin�");
define("INSLAN52", "e107 a �t� correctement install�!<br />Pour des raison de s�curit� vous devez maintenant mettre les permissions du fichier<br /><b>e107_config.php</b> � 644.<br />Effacez �galement /install.php de votre serveur apr�s avoir cliqu� le bouton ci-dessous");
define("INSLAN53", "Cliquer ici pour aller sur votre site web!");
define("INSLAN54", "Impssible de lire le fichier de donn�es sql<br /><br />Assurez vous que le fichier <b>core_sql.php</b> existe dans le r�pertoire <b>/e107_admin/sql</b>.");
define("INSLAN55", "e107 n\'apas pu cr�er toutes les tables dans la base de donn�es.<br />Effacez la base et corrigez les probl�mes avant de r�essayer.");
define("INSLAN56", "Bienvenue sur votre nouveau site web!");

define("INSLAN57", "e107 a �t� install� avec succ�s et est maintenant pr�s � recevoir un contenu.<br />Votre section d\'administration est situ�e <a href='e107_admin/admin.php'>ici</a>, cliquez maintenant pour y allez. Pour vous connecter vous devrez utiliser le Nom utilisateur et le Mot de passe que vous avez entr� durant la proc�dure d\'installation.");
define("INSLAN58", "vous trouverez la FAQ et la documentation ici.");
define("INSLAN59", "Merci d\'essayer e107, nous esp�rons qu\'il r�pondra � vos attentes pour votre site web.\n(Vous pouvez effacer ce message de votre section admin.)");
define("INSLAN60", "cochez pour cr�er");
define("INSLAN61", "dossier");
define("INSLAN62", "ou");
define("INSLAN63", "Erreur de permission fichier");
define("INSLAN64", "Ce fichier a �t� g�n�r� par le script d\'installation.");

?>