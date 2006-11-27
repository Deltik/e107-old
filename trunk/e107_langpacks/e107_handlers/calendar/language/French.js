// ** I18N

// Calendar Fr language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Dimanche",
 "Lundi",
 "Mardi",
 "Mercredi",
 "Jeudi",
 "Vendredi",
 "Samedi",
 "Dimanche");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Dim",
 "Lun",
 "Mar",
 "Mer",
 "Jeu",
 "Ven",
 "Sam",
 "Dim");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("Janvier",
 "F&eacute;vrier",
 "Mars",
 "Avril",
 "Mai",
 "Juin",
 "Juillet",
 "Ao&ucirc;t",
 "Septembre",
 "Octobre",
 "Novembre",
 "D&eacute;cembre");

// short month names
Calendar._SMN = new Array
("Jan",
 "F&eacute;v",
 "Mars",
 "Avr",
 "Mai",
 "Jui",
 "Juil",
 "Ao&ucirc;",
 "Sep",
 "Oct",
 "Nov",
 "D&eacute;c");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "A propos du calendrier";

Calendar._TT["ABOUT"] =
"Sélecteur Date/Heure DHTML\n" +
"(c) dynarch.com 2002-2005 / Auteur: Mihai Bazon\n" + // don't translate this ;-)
"Pour obtenir la dernière version, visitez: http://www.dynarch.com/projects/calendar/\n" +
"Distribué sous GNU LGPL.  Voir http://gnu.org/licenses/lgpl.html pour les détails." +
"\n\n" +
"Sélection de la date:\n" +
"- Utiliser les boutons \xab, \xbb pour sélectionner l&#39;année\n" +
"- Utiliser les boutons " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pour sélectionner le mois\n" +
"- maintenir le bouton de la souris enfoncé ou appuyer n&#39;importe quel boutons ci-dessus pour une sélection rapide.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Sélection de l&#39;heure:\n" +
"- Cliquer sur heures ou minutes pour incrémenter\n" +
"- ou Maj-clic pour décrémenter\n" +
"- ou cliquer et glisser-déplacer pour une sélection plus rapide";

Calendar._TT["PREV_YEAR"] = "Année précédente (maintenir pour le menu)";
Calendar._TT["PREV_MONTH"] = "Mois précédent (maintenir pour le menu)";
Calendar._TT["GO_TODAY"] = "Aller à aujourd&#39;hui";
Calendar._TT["NEXT_MONTH"] = "Mois suivant (maintenir pour le menu)";
Calendar._TT["NEXT_YEAR"] = "Année suivante (maintenir pour le menu)";
Calendar._TT["SEL_DATE"] = "Sélectionner la date";
Calendar._TT["DRAG_TO_MOVE"] = "Glisser pour déplacer";
Calendar._TT["PART_TODAY"] = " (aujourd'hui)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Afficher %s en premier";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Fermer";
Calendar._TT["TODAY"] = "Aujourd&#39hui";
Calendar._TT["TIME_PART"] = "(Maj-)Clic ou glisser pour changer la valeur";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a %e %b ";

Calendar._TT["WK"] = "Sem.";
Calendar._TT["TIME"] = "Heure:";
