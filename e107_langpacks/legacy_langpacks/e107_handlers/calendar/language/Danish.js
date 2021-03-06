// ** I18N

// Calendar DA language
// Author: Jesper Olesen, <e107@e107.dk>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("S&oslash;ndag",
 "Mandag",
 "Tirsdag",
 "Onsdag",
 "Torsdag",
 "Fredag",
 "L&oslash;rdag",
 "S&oslash;ndag");

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
("S&oslash;n",
 "Man",
 "Tir",
 "Ons",
 "Tor",
 "Fre",
 "L&oslash;r",
 "S&oslash;n");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("Januar",
 "Februar",
 "Marts",
 "April",
 "Maj",
 "Juni",
 "Juli",
 "August",
 "September",
 "Oktober",
 "November",
 "December");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "Maj",
 "Jun",
 "Jul",
 "Aug",
 "Sep",
 "Okt",
 "Nov",
 "Dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Om kalenderen";

Calendar._TT["ABOUT"] =
"DHTML Dato/Tid V&aelig;lger\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For seneste version bes&oslash;g: http://www.dynarch.com/projects/calendar/\n" +
"Distribueret under GNU LGPL.  Se http://gnu.org/licenses/lgpl.html for detailier." +
"\n\n" +
"Dato valg:\n" +
"- Brug \xab, \xbb knapperne til at v&aelig;lge &aring;r\n" +
"- Brug " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " knapperne til at v&aelig;lge m&aring;ned\n" +
"- Hold museknappen p&aring; hvilken som helst af de forovenv&aelig;rende for hurtigere valg.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Tid valg:\n" +
"- Klik p&aring; hvilken som helst af tids delene for at &oslash;ge den\n" +
"- eller Shift-klik for at formindske den\n" +
"- eller klik og tr&aelig;k for hurtigere valg.";

Calendar._TT["PREV_YEAR"] = "Forrige. &aring;r (hold for menu)";
Calendar._TT["PREV_MONTH"] = "Forrige. m&aring;ned (hold for menu)";
Calendar._TT["GO_TODAY"] = "G&aring; Idag";
Calendar._TT["NEXT_MONTH"] = "N&aelig;ste m&aring;ned (hold for menu)";
Calendar._TT["NEXT_YEAR"] = "N&aelig;ste &aring;r (hold for menu)";
Calendar._TT["SEL_DATE"] = "V&aelig;lg dato";
Calendar._TT["DRAG_TO_MOVE"] = "Tr&aelig;k for at flytte";
Calendar._TT["PART_TODAY"] = " (idag)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Vis %s f&oslash;rst";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Luk";
Calendar._TT["TODAY"] = "Idag";
Calendar._TT["TIME_PART"] = "(Shift-)Klik eller tr&aelig;k for at &aelig;ndre v&aelig;rdi";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Tid:";