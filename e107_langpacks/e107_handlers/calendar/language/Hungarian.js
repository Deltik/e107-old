// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Vas�rnap",
 "H�tf�",
 "Kedd",
 "Szerda",
 "Cs�t�rt�k",
 "P�ntek",
 "Szombat",
 "Vas�rnap");

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
("V",
 "H",
 "K",
 "Sze",
 "Cs",
 "P",
 "Szo",
 "V");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("Janu�r",
 "Febru�r",
 "M�rcius",
 "�prilis",
 "M�jus",
 "J�nius",
 "J�lius",
 "Augusztus",
 "Szeptember",
 "Okt�ber",
 "November",
 "December");

// short month names
Calendar._SMN = new Array
("Jan",
 "Febr",
 "M�rc",
 "�pr",
 "M�j",
 "J�n",
 "J�l",
 "Aug",
 "Szept",
 "Okt",
 "Nov",
 "Dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "A napt�rr�l";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"A legfrissebb verzi� megtekint�s�hez l�togass ide: http://www.dynarch.com/projects/calendar/\n" +
"Kiadva a GNU LGPL alatt.  A r�szletek megtekint�se itt http://gnu.org/licenses/lgpl.html ." +
"\n\n" +
"D�tum kiv�laszt�s:\n" +
"- Haszn�ld a \xab, \xbb gombokat az �v kiv�laszt�s�hoz\n" +
"- Haszn�ld a " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " gombot a h�nap kiv�laszt�s�hoz\n" +
"- Tartsd az eg�r gombot b�rmelyik gomb felett a gyorsabb kiv�laszt�shoz.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Id� kiv�laszt�sa:\n" +
"- Katt az id� b�rmely r�sz�re a n�vel�s�hez\n" +
"- vagy Shift-katt a cs�kkent�shez\n" +
"- vagy katt �s fogd a gyorsabb kiv�laszt�shoz.";

Calendar._TT["PREV_YEAR"] = "El�z� �v (tartsd a men�h�z)";
Calendar._TT["PREV_MONTH"] = "El�z� h�nap (tartsd a men�h�z)";
Calendar._TT["GO_TODAY"] = "Ugr�s mai napra";
Calendar._TT["NEXT_MONTH"] = "K�vetkez� h�nap (tartsd a men�h�z)";
Calendar._TT["NEXT_YEAR"] = "K�vetkez� �v (tartsd a men�h�z)";
Calendar._TT["SEL_DATE"] = "D�tum kiv�laszt�sa";
Calendar._TT["DRAG_TO_MOVE"] = "Fogd a mozgat�shoz";
Calendar._TT["PART_TODAY"] = " (ma)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "%s els�k�nt megjelen�t";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Bez�r";
Calendar._TT["TODAY"] = "Ma";
Calendar._TT["TIME_PART"] = "(Shift-)Katt vagy fogd az �rt�k megv�ltoztat�s�hoz";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Id�:";
